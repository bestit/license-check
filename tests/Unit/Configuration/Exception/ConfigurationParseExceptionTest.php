<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck\Configuration\Exception;

use PHPUnit\Framework\TestCase;

/**
 * Tests for the class ConfigurationParseException.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck\Configuration\Exception
 */
class ConfigurationParseExceptionTest extends TestCase
{
    /**
     * The test fixture.
     *
     * @var ConfigurationParseException $fixture
     */
    private ConfigurationParseException $fixture;

    /**
     * Set up the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->fixture = new ConfigurationParseException();
    }

    /**
     * Test the inheritance of the exception.
     *
     * @return void
     */
    public function testInheritance(): void
    {
        self::assertInstanceOf(ConfigurationException::class, $this->fixture);
    }
}
