<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck\Command;

use BestIt\LicenseCheck\Checker;
use BestIt\LicenseCheck\Configuration\Configuration;
use BestIt\LicenseCheck\Configuration\ConfigurationLoader;
use BestIt\LicenseCheck\Result;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Tests for the class LicenseCheckCommand.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck\Command
 */
class LicenseCheckCommandTest extends TestCase
{
    /**
     * The test fixture.
     *
     * @var LicenseCheckCommand $fixture
     */
    private LicenseCheckCommand $fixture;

    /**
     * Mock object for the license checker.
     *
     * @var Checker|MockObject $checker
     */
    private MockObject | Checker $checker;

    /**
     * Mock object for the configuration loader.
     *
     * @var ConfigurationLoader|MockObject $configurationLoader
     */
    private ConfigurationLoader | MockObject $configurationLoader;

    /**
     * Data provider to test the execution method.
     *
     * @return array[]
     */
    public function getExecutionDataProvider(): array
    {
        return [
            // Actual working directory. Errors not ignores. No custom config. Result has no violations.
            [
                [],
                0,
                getcwd(),
                [getcwd() . '/license-check.yml'],
                [],
            ],
            // Actual working directory. Errors not ignores. No custom config. Result has violations.
            [
                [],
                1,
                getcwd(),
                [getcwd() . '/license-check.yml'],
                ['VIOLATION'],
            ],
            // Actual working directory. Errors are ignores. No custom config. Result has violations.
            [
                [
                    '--ignore-errors' => true,
                ],
                0,
                getcwd(),
                [getcwd() . '/license-check.yml'],
                ['VIOLATION'],
            ],
            // Custom working directory. Errors not ignores. No custom config. Result has no violations.
            [
                [
                    'directory' => '/test-directory',
                ],
                0,
                '/test-directory',
                ['/test-directory/license-check.yml'],
                [],
            ],
            // Custom working directory. Errors not ignores. No custom config. Result has violations.
            [
                [
                    'directory' => '/test-directory',
                ],
                1,
                '/test-directory',
                ['/test-directory/license-check.yml'],
                ['VIOLATION'],
            ],
            // Custom working directory. Errors are ignores. No custom config. Result has violations.
            [
                [
                    'directory' => '/test-directory',
                    '--ignore-errors' => true,
                ],
                0,
                '/test-directory',
                ['/test-directory/license-check.yml'],
                ['VIOLATION'],
            ],
            // Custom working directory. Errors not ignores. Custom config. Result has no violations.
            [
                [
                    'directory' => '/test-directory',
                    '--configuration' => ['/testconfig.yml'],
                ],
                0,
                '/test-directory',
                ['/testconfig.yml'],
                [],
            ],
            // Custom working directory. Errors not ignores. No custom config. Result has violations.
            [
                [
                    'directory' => '/test-directory',
                    '--configuration' => ['/testconfig.yml'],
                ],
                1,
                '/test-directory',
                ['/testconfig.yml'],
                ['VIOLATION'],
            ],
            // Custom working directory. Errors are ignores. No custom config. Result has violations.
            [
                [
                    'directory' => '/test-directory',
                    '--ignore-errors' => true,
                    '--configuration' => ['/testconfig.yml'],
                ],
                0,
                '/test-directory',
                ['/testconfig.yml'],
                ['VIOLATION'],
            ],
        ];
    }

    /**
     * Set up the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->fixture = new LicenseCheckCommand(
            $this->checker = $this->createMock(Checker::class),
            $this->configurationLoader = $this->createMock(ConfigurationLoader::class),
        );
    }

    /**
     * Test the command definition
     *
     * @return void
     */
    public function testDefinition(): void
    {
        self::assertEquals('license-check', $this->fixture->getName());

        self::assertTrue($this->fixture->getDefinition()->hasArgument('directory'));
        self::assertFalse($this->fixture->getDefinition()->getArgument('directory')->isRequired());

        self::assertTrue($this->fixture->getDefinition()->hasOption('configuration'));
        self::assertTrue($this->fixture->getDefinition()->getOption('configuration')->isValueRequired());
        self::assertTrue($this->fixture->getDefinition()->getOption('configuration')->isArray());

        self::assertTrue($this->fixture->getDefinition()->hasOption('ignore-errors'));
        self::assertFalse($this->fixture->getDefinition()->getOption('ignore-errors')->isValueOptional());
        self::assertFalse($this->fixture->getDefinition()->getOption('ignore-errors')->isValueRequired());
    }

    /**
     * Test that command execution runs correctly.
     *
     * @dataProvider getExecutionDataProvider
     *
     * @param string[] $input
     * @param int $resultCode
     * @param string $directory
     * @param array $configFiles
     * @param string[] $violations
     *
     * @return void
     */
    public function testExecution(
        array $input,
        int $resultCode,
        string $directory,
        array $configFiles,
        array $violations,
    ): void {
        $this
            ->configurationLoader
            ->method('load')
            ->with($configFiles)
            ->willReturn($configuration = $this->createMock(Configuration::class));

        $resultSet = new Result();

        foreach ($violations as $violation) {
            $resultSet->addViolation($violation);
        }

        $this
            ->checker
            ->method('validate')
            ->with($configuration, $directory)
            ->willReturn($resultSet);

        $commandTester = new CommandTester($this->fixture);
        self::assertEquals($resultCode, $commandTester->execute($input));
    }

    /**
     * Test that command extends the symfony command.
     *
     * @return void
     */
    public function testInheritance(): void
    {
        self::assertInstanceOf(Command::class, $this->fixture);
    }
}
