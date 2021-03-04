<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck\Command\Exception;

use BestIt\LicenseCheck\Exception\LicenseCheckException;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the class CommandException.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck\Command\Exception
 */
class CommandExceptionTest extends TestCase
{
    /**
     * The test fixture.
     *
     * @var CommandException $fixture
     */
    private CommandException $fixture;

    /**
     * Set up the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->fixture = new CommandException();
    }

    /**
     * Test the inheritance of the exception.
     *
     * @return void
     */
    public function testInheritance(): void
    {
        self::assertInstanceOf(LicenseCheckException::class, $this->fixture);
    }
}
