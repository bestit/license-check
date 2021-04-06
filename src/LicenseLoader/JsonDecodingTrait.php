<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck\LicenseLoader;

use BestIt\LicenseCheck\LicenseLoader\Exception\LicenseLoaderException;
use SplFileInfo;
use function is_array;
use function json_decode;
use function sprintf;

trait JsonDecodingTrait
{
    protected function getPackagesLicensesOfFile(SplFileInfo $file): array
    {
        $decodedContent = json_decode($file->getContents(), true);

        if (!is_array($decodedContent)) {
            throw new LicenseLoaderException(sprintf('Cannot decode content of file %s', $file->getPathname()));
        }

        return $this->parseLibraryFileArray($decodedContent);
    }

    private abstract function parseLibraryFileArray(array $rawData): array;
}