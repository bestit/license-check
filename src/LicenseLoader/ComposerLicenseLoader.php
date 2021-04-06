<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck\LicenseLoader;

/**
 * License loader for composer packages.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck\LicenseLoader
 */
class ComposerLicenseLoader extends LicenseLoaderAbstract
{
    use JsonDecodingTrait;

    private const LOADER_NAME = 'composer';

    private const FILE_PATTERN = 'composer.lock';

    protected function getFilePattern(): string
    {
        return self::FILE_PATTERN;
    }

    public function getName(): string
    {
        return self::LOADER_NAME;
    }

    private function parseLibraryFileArray(array $rawData): array
    {
        $result = [];

        // ToDo: Check json structure.
        foreach ($rawData['packages'] as $package) {
            $result[$package['name']] = $package['license'] ?? [];
        }

        return $result;
    }
}
