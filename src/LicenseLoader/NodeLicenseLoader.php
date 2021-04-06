<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck\LicenseLoader;

/**
 * License loader for node packages.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck\LicenseLoader
 */
class NodeLicenseLoader extends LicenseLoaderAbstract
{
    use JsonDecodingTrait;

    private const FILE_PATTERN = '/node_modules\/([A-Za-z0-9]|-|_)*\/package.json/';
    private const LOADER_NAME = 'node';

    protected function getFilePattern(): string
    {
        return self::FILE_PATTERN;
    }

    public function getName(): string
    {
        return self::LOADER_NAME;
    }

    protected function getSearchMethod(): string
    {
        return 'path';
    }

    private function parseLibraryFileArray(array $rawData): array
    {
        return [$rawData['name'] => isset($rawData['license']) ? [$rawData['license']] : []];
    }
}
