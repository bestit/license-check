<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck\Configuration;

use PHPUnit\Framework\TestCase;

/**
 * Tests for the class Configuration.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck\Configuration
 */
class ConfigurationExceptionTest extends TestCase
{
    /**
     * The test fixture.
     *
     * @var Configuration $fixture
     */
    private Configuration $fixture;

    /**
     * Dummy data for allowed licenses.
     *
     * @var string[] $allowedLicenses
     */
    private array $allowedLicenses = [
        'License-A',
        'License-B',
    ];

    /**
     * Dummy data for allowed packages.
     *
     * @var string[][] $allowedPackages
     */
    private array $allowedPackages = [
        'composer' => [
            'vender1/package1',
            'vender1/package2',
            'vender2/package1',
            'vender2/package2',
        ],
        'npm' => [
            'vender1/package1',
            'vender1/package2',
            'vender2/package1',
            'vender2/package2',
        ],
    ];

    /**
     * Set up the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->fixture = new Configuration(
            $this->allowedLicenses,
            $this->allowedPackages,
        );
    }

    /**
     * Test that allowed licenses are returned correctly.
     *
     * @return void
     */
    public function testGetAllowedLicenses(): void
    {
        self::assertEquals($this->allowedLicenses, $this->fixture->getAllowedLicenses());
    }

    /**
     * Test that allowed packages are returned correctly.
     *
     * @return void
     */
    public function testGetAllowedPackages(): void
    {
        foreach ($this->allowedPackages as $type => $packages) {
            self::assertEquals($this->allowedPackages[$type], $this->fixture->getAllowedPackages($type));
        }
    }
}
