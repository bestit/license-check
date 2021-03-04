<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck\Configuration\Exception;

use PHPUnit\Framework\TestCase;

/**
 * Tests for the class ConfigurationNotFoundException.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck\Configuration\Exception
 */
class ConfigurationNotFoundExceptionTest extends TestCase
{
    /**
     * The test fixture.
     *
     * @var ConfigurationNotFoundException $fixture
     */
    private ConfigurationNotFoundException $fixture;

    /**
     * Set up the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->fixture = new ConfigurationNotFoundException();
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
