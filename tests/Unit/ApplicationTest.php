<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck;

use BestIt\LicenseCheck\Command\LicenseCheckCommand;
use BestIt\LicenseCheck\Configuration\ConfigurationLoader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application as SymfonyApplication;

/**
 * Tests for the class Application.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck
 */
class ApplicationTest extends TestCase
{
    /**
     * The test fixture.
     *
     * @var Application $fixture
     */
    private Application $fixture;

    /**
     * Dummy default command.
     *
     * @var LicenseCheckCommand
     */
    private LicenseCheckCommand $defaultCommand;

    /**
     * Default command name.
     *
     * @var string $commandName
     */
    private string $commandName = 'license-check';

    /**
     * Set up the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->defaultCommand = new LicenseCheckCommand(
            $this->createMock(Checker::class),
            $this->createMock(ConfigurationLoader::class),
        );

        $this->fixture = new Application($this->defaultCommand);
    }

    /**
     * Test the inheritance of the exception.
     *
     * @return void
     */
    public function testApplication(): void
    {
        self::assertEquals($this->commandName, $this->fixture->getName());
        self::assertTrue($this->fixture->isSingleCommand());
        self::assertSame($this->defaultCommand, $this->fixture->find($this->commandName));
    }

    /**
     * Test the inheritance of the exception.
     *
     * @return void
     */
    public function testInheritance(): void
    {
        self::assertInstanceOf(SymfonyApplication::class, $this->fixture);
    }
}
