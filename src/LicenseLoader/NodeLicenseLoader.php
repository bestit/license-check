<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck\LicenseLoader;

use BestIt\LicenseCheck\LicenseLoader\Result\Result;

/**
 * License loader for node packages.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck\LicenseLoader
 */
class NodeLicenseLoader extends AbstractLicenseLoader
{
    /**
     * Pattern for node files.
     *
     * @var string|null
     */
    protected ?string $searchPattern = '/node_modules\/([A-Za-z0-9]|-|_)*\/package.json/';

    /**
     * Parse package.json content.
     *
     * @param mixed[] $decodedContent
     * @param Result $result
     *
     * @return void
     */
    protected function parseFile(array $decodedContent, Result $result): void
    {
        // ToDo: Check json structure.
        $result->add(
            (string) $decodedContent['name'],
            isset($decodedContent['license']) ? [$decodedContent['license']] : [],
        );
    }

    /**
     * Get name of the license loader.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'node';
    }
}
