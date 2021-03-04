<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck\Configuration\Exception;

use BestIt\LicenseCheck\Exception\LicenseCheckException;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the class ConfigurationException.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck\Configuration\Exception
 */
class ConfigurationExceptionTest extends TestCase
{
    /**
     * The test fixture.
     *
     * @var ConfigurationException $fixture
     */
    private ConfigurationException $fixture;

    /**
     * Set up the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->fixture = new ConfigurationException();
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
