<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck\Command;

use BestIt\LicenseCheck\Checker;
use BestIt\LicenseCheck\Command\Exception\CommandException;
use BestIt\LicenseCheck\Configuration\ConfigurationLoader;
use BestIt\LicenseCheck\Configuration\Exception\ConfigurationNotFoundException;
use BestIt\LicenseCheck\Configuration\Exception\ConfigurationParseException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function defined;

/**
 * Command to check package licenses.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck\Command
 */
class LicenseCheckCommand extends Command
{
    /**
     * Constant for the directory cli argument.
     *
     * @var string ARGUMENT_DIRECTORY
     */
    private const ARGUMENT_DIRECTORY = 'directory';

    /**
     * Constant for the configuration cli option.
     *
     * @var string OPTION_CONFIGURATION
     */
    private const OPTION_CONFIGURATION = 'configuration';

    /**
     * Constant for the depth cli option.
     *
     * @var string OPTION_IGNORE_ERRORS
     */
    private const OPTION_DEPTH = 'depth';

    /**
     * Constant for the ignore-errors cli option.
     *
     * @var string OPTION_IGNORE_ERRORS
     */
    private const OPTION_IGNORE_ERRORS = 'ignore-errors';

    /**
     * Dependency to the checker class which do the license validation.
     *
     * @var Checker $checker
     */
    private Checker $checker;

    /**
     * Dependency to the loader to get the configuration object.
     *
     * @var ConfigurationLoader $configurationLoader
     */
    private ConfigurationLoader $configurationLoader;

    /**
     * Create license check command instance.
     *
     * @param Checker $checker Dependency to the checker class which do the license validation.
     * @param ConfigurationLoader $configurationLoader Dependency to the loader to get the configuration object.
     */
    public function __construct(Checker $checker, ConfigurationLoader $configurationLoader)
    {
        $this->checker = $checker;
        $this->configurationLoader = $configurationLoader;

        parent::__construct('license-check');
    }

    /**
     * Add needed arguments and options to the command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Tool to check licenses of used packages.')
            ->addArgument(
                self::ARGUMENT_DIRECTORY,
                InputArgument::OPTIONAL,
                __DIR__,
            )
            ->addOption(
                self::OPTION_CONFIGURATION,
                'c',
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of configuration files.',
            )
            ->addOption(
                self::OPTION_IGNORE_ERRORS,
                null,
                InputOption::VALUE_NONE,
                'Don\'t return an error code.',
            )
            ->addOption(
                self::OPTION_DEPTH,
                null,
                InputOption::VALUE_REQUIRED,
                'Set the maximum depth of the directory iterators.',
            );
    }

    /**
     * Execution method for the cli command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @throws ConfigurationNotFoundException Exception if configuration is not found.
     * @throws ConfigurationParseException Exception if configuration cannot be parsed.
     * @throws CommandException Thrown if directory is not readable
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!is_string($workingDirectory = $input->getArgument(self::ARGUMENT_DIRECTORY))) {
            $workingDirectory = getcwd();
        }

        if (!is_string($workingDirectory)) {
            throw new CommandException('Cannot read working directory.');
        }

        $configFiles = $input->getOption(self::OPTION_CONFIGURATION);

        assert(is_array($configFiles));
        if (count($configFiles) === 0) {
            $configFiles[] = $workingDirectory . '/license-check.yml';
        }

        $configuration = $this->configurationLoader->load($configFiles);

        if (($depth = $input->getOption(self::OPTION_DEPTH)) !== null) {
            assert(is_string($depth));
            $depth = (int) $depth;
        }

        $resultSet = $this->checker->validate($configuration, $workingDirectory, $depth);

        $resultCode = defined('static::SUCCESS') ? static::SUCCESS : 0;

        if ($resultSet->isPassed()) {
            $output->writeln('<info>License check passed!</info>');
        } else {
            if (!$input->getOption(self::OPTION_IGNORE_ERRORS)) {
                $resultCode = defined('static::FAILURE') ? static::FAILURE : 1;
            }

            foreach ($resultSet->getViolations() as $violation) {
                $output->writeln(
                    sprintf(
                        '<comment>%s</comment>',
                        $violation,
                    ),
                );
            }

            $output->writeln('<error>License check failed!</error>');
        }

        return $resultCode;
    }
}
