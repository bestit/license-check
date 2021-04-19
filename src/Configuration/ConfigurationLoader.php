<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck\Configuration;

use BestIt\LicenseCheck\Configuration\Exception\ConfigurationNotFoundException;
use BestIt\LicenseCheck\Configuration\Exception\ConfigurationParseException;
use Symfony\Component\Yaml\Yaml;
use Throwable;

/**
 * Loader to create a configuration object by a configuration yaml file.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck\Configuration
 */
class ConfigurationLoader
{
    /**
     * Constant for the array key of allowed licenses.
     *
     * @var string KEY_ALLOWED_LICENSES
     */
    private const KEY_ALLOWED_LICENSES = 'allowed-licenses';

    /**
     * Constant for the array key of allowed packages.
     *
     * @var string KEY_ALLOWED_PACKAGES
     */
    private const KEY_ALLOWED_PACKAGES = 'allowed-packages';


    /**
     * Extract allowed licenses from whole configuration array and validates the type.
     *
     * @param string[] $items
     *
     * @return array<string>
     */
    private function getAllowedLicenses(array $items): array
    {
        $result = [];

        foreach ($items as $item) {
            if (!is_string($item)) {
                throw new ConfigurationParseException('Invalid value.');
            }
            $result[] = $item;
        }

        return $result;
    }

    /**
     * Extract allowed packages from whole configuration array and validates the type.
     *
     * @param string[][] $list
     *
     * @return array<string, array<string>>
     */
    private function getAllowedPackages(array $list): array
    {
        $result = [];

        foreach ($list as $type => $items) {
            if (!is_array($items)) {
                $items = [];
            }
            foreach ($items as $item) {
                if (!is_string($item)) {
                    throw new ConfigurationParseException('Invalid value.');
                }
                $result[$type][] = $item;
            }
        }

        return $result;
    }

    /**
     * Create a configuration object from a file.
     *
     * @param string[] $configFiles Array of configuration files with path.
     *
     * @throws ConfigurationNotFoundException Exception if configuration file was not found.
     * @throws ConfigurationParseException Exception if configuration cannot be parsed.
     *
     * @return Configuration
     */
    public function load(array $configFiles): Configuration
    {
        $configArrays = [];
        foreach ($configFiles as $configFile) {
            $configArrays[] = $this->parseConfigFile($configFile);
        }

        $mergedConfig = [
            'allowed_licenses' => [],
            'allowed_packages' => [],
        ];

        foreach ($configArrays as $configArray) {
            $mergedConfig = array_merge_recursive($mergedConfig, $configArray);
        }

        return new Configuration(
            $mergedConfig['allowed_licenses'],
            $mergedConfig['allowed_packages'],
        );
    }

    /**
     * Reads a single config file and returns the config as array.
     *
     * @param string $fileName Path to config file.
     *
     * @throws ConfigurationNotFoundException Exception if configuration file was not found.
     * @throws ConfigurationParseException Exception if configuration cannot be parsed.
     *
     * @return array<string, array<array<string>|string>>
     */
    private function parseConfigFile(string $fileName): array
    {
        if (!file_exists($fileName)) {
            throw new ConfigurationNotFoundException(sprintf('Configuration file %s not found.', $fileName));
        }

        try {
            $yaml = Yaml::parseFile($fileName);

            $allowedLicences = $this->getAllowedLicenses($yaml[self::KEY_ALLOWED_LICENSES] ?? []);
            $allowedPackages = $this->getAllowedPackages($yaml[self::KEY_ALLOWED_PACKAGES] ?? []);
        } catch (Throwable $e) {
            throw new ConfigurationParseException('Configuration file cannot be parsed.', 0, $e);
        }

        return ['allowed_licenses' => $allowedLicences, 'allowed_packages' => $allowedPackages];
    }
}
