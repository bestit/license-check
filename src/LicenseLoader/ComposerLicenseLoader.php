<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck\LicenseLoader;

use BestIt\LicenseCheck\LicenseLoader\Result\Result;

/**
 * License loader for composer packages.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck\LicenseLoader
 */
class ComposerLicenseLoader extends AbstractLicenseLoader
{
    /**
     * Pattern for composer files.
     *
     * @var string|null
     */
    protected ?string $searchPattern = 'composer.lock';

    /**
     * Parse composer.lock content.
     *
     * @param mixed[] $decodedContent
     * @param Result $result
     *
     * @return void
     */
    protected function parseFile(array $decodedContent, Result $result): void
    {
        // ToDo: Check json structure.
        foreach ($decodedContent['packages'] as $package) {
            $result->add(
                (string) $package['name'],
                $package['license'] ?? [],
            );
        }
    }

    /**
     * Get name of the license loader.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'composer';
    }
}
