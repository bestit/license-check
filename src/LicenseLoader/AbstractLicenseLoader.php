<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck\LicenseLoader;

use BestIt\LicenseCheck\LicenseLoader\Exception\LicenseLoaderException;
use BestIt\LicenseCheck\LicenseLoader\Exception\PatternNotDefinedException;
use BestIt\LicenseCheck\LicenseLoader\Result\Result;
use Symfony\Component\Finder\Finder;

/**
 * Abstract license loader.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck\LicenseLoader
 */
abstract class AbstractLicenseLoader implements LicenseLoaderInterface
{
    /**
     * Finder to get composer.json files.
     *
     * @var Finder $finder
     */
    private Finder $finder;

    /**
     * Search pattern to get license files.
     *
     * @var string|null $searchPattern
     */
    protected ?string $searchPattern = null;

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
     * @param string $path Search path.
     * @param int|null $depth (Optional) Search depth.
     *
     * @return array<array<string>>
     */
    public function getLicenses(string $path, ?int $depth = null): array
    {
        $result = new Result();

        if ($this->searchPattern === null) {
            throw new PatternNotDefinedException();
        }

        $iterator = $this->finder->path($this->searchPattern)->in($path);

        if ($depth !== null) {
            $iterator->depth($depth);
        }

        foreach ($iterator as $file) {
            $filePath = $file->getPathname();
            $content = $file->getContents();
            if (!is_string($content)) {
                throw new LicenseLoaderException(sprintf('Cannot read content of file %s', $filePath));
            }

            $decodedContent = json_decode($content, true);
            if (!is_array($decodedContent)) {
                throw new LicenseLoaderException(sprintf('Cannot decode content of file %s', $filePath));
            }

            $this->parseFile($decodedContent, $result);
        }

        return $result->toArray();
    }

    /**
     * Decode file contents.
     *
     * @param mixed[] $decodedContent Array of json file content.
     * @param Result $result License result.
     *
     * @return void
     */
    abstract protected function parseFile(array $decodedContent, Result $result): void;
}
