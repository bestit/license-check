<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck\Configuration;

/**
 * Configuration objects.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck\Configuration
 */
class Configuration
{
    /**
     * @var string[] $allowedLicenses List of allowed licenses.
     */
    private array $allowedLicenses;

    /**
     * @var string[][] $allowedPackages List of allowed packages.
     */
    private array $allowedPackages;

    /**
     * Create the instance of the configuration.
     *
     * @param string[] $allowedLicenses List of allowed licenses.
     * @param string[][] $allowedPackages List of allowed packages.
     */
    public function __construct(array $allowedLicenses, array $allowedPackages)
    {
        $this->allowedLicenses = $allowedLicenses;
        $this->allowedPackages = $allowedPackages;
    }

    /**
     * Get list / array of allowed licenses.
     *
     * @return array<string>
     */
    public function getAllowedLicenses(): array
    {
        return $this->allowedLicenses;
    }

    /**
     * Get list of the allowed packages for the specified type.
     *
     * @param string $type Type of the packages.
     *
     * @return array<string>
     */
    public function getAllowedPackages(string $type): array
    {
        return $this->allowedPackages[$type] ?? [];
    }
}
