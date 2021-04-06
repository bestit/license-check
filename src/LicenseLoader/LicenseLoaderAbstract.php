<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck\LicenseLoader;

use BestIt\LicenseCheck\LicenseLoader\Exception\LicenseLoaderException;
use SplFileInfo;
use Symfony\Component\Finder\Finder;
use function sprintf;

abstract class LicenseLoaderAbstract implements LicenseLoaderInterface
{
    /**
     * Finder to get composer.json files.
     *
     * @var Finder $finder
     */
    protected Finder $finder;

    /**
     * ComposerLicenseLoader constructor.
     *
     * @param Finder $finder
     */
    public function __construct(Finder $finder)
    {
        $this->finder = $finder;
    }

    private function getFileIterator(string $path): iterable
    {
        return $this->finder->{$this->getSearchMethod()}($this->getFilePattern())->in($path);
    }

    protected abstract function getFilePattern(): string;

    protected function getSearchMethod(): string
    {
        return 'name';
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

        foreach ($this->getFileIterator($path) as $file) {
            if (!$file->isReadable()) {
                throw new LicenseLoaderException(sprintf(
                    'Cannot read content of file %s',
                    $file->getPathname(),
                ));
            }

            $result += $this->getPackagesLicensesOfFile($file);
        }

        return $result;
    }

    protected abstract function getPackagesLicensesOfFile(SplFileInfo $file): array;
}