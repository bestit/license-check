<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck\LicenseLoader;

use BestIt\LicenseCheck\LicenseLoader\Exception\LicenseLoaderException;
use Symfony\Component\Finder\Finder;

/**
 * License loader for node packages.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck\LicenseLoader
 */
class NodeLicenseLoader implements LicenseLoaderInterface
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

        foreach ($this->finder->path('/node_modules\/([A-Za-z0-9]|-|_)*\/package.json/')->in($path) as $file) {
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
            $result[$decodedContent['name']] = isset($decodedContent['license']) ? [$decodedContent['license']] : [];
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
        return 'node';
    }
}
