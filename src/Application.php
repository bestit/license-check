<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck;

use BestIt\LicenseCheck\Exception\LicenseCheckException;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Command\Command;

/**
 * Application which provides the console command.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck
 */
class Application extends SymfonyApplication
{
    /**
     * Start application and register the default command.
     *
     * @param Command $defaultCommand
     */
    public function __construct(Command $defaultCommand)
    {
        $commandName = $defaultCommand->getName();

        if (!is_string($commandName)) {
            throw new LicenseCheckException('Invalid command name.');
        }

        parent::__construct($commandName);
        $this->add($defaultCommand);
        $this->setDefaultCommand($commandName, true);
    }
}
