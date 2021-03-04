<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck\LicenseLoader;

use BestIt\LicenseCheck\LicenseLoader\Exception\LicenseLoaderException;
use Symfony\Component\Finder\Finder;

/**
 * License loader for composer packages.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck\LicenseLoader
 */
class ComposerLicenseLoader implements LicenseLoaderInterface
{
    /**
     * Finder to get composer.json files.
     *
     * @var Finder $finder
     */
    private Finder $finder;

    /**
     * ComposerLicenseLoader constructor.
     *
     * @param Finder $finder
     */
    public function __construct(Finder $finder)
    {
        $this->finder = $finder;
    }

    /**
     * Get used licenses in the specified path.
     *
     * @param string $path
     *
     * @return array<array<string>>
     */
    public function getLicenses(string $path): array
    {
        $result = [];

        foreach ($this->finder->name('composer.lock')->in($path) as $file) {
            $filePath = $file->getPathname();
            $content = $file->getContents();
            if (!is_string($content)) {
                throw new LicenseLoaderException(sprintf('Cannot read content of file %s', $filePath));
            }

            $decodedContent = json_decode($content, true);
            if (!is_array($decodedContent)) {
                throw new LicenseLoaderException(sprintf('Cannot decode content of file %s', $filePath));
            }

            // ToDo: Check json structure.
            foreach ($decodedContent['packages'] as $package) {
                $result[$package['name']] = $package['license'] ?? [];
            }
        }

        return $result;
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
