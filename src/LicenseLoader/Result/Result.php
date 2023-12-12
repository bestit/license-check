<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck\LicenseLoader\Result;

use BestIt\LicenseCheck\LicenseLoader\Exception\LicenseLoaderException;
use Symfony\Component\Finder\Finder;

/**
 * Result set of licenses.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck\LicenseLoader\Result
 */
class Result
{
    /**
     * Internal data storage.
     *
     * @var array<array> $data
     */
    private array $data = [];

    /**
     * Add licenses to result set.
     *
     * @param string $package Name of package.
     * @param string[] $licenses Array of package licenses.
     *
     * @return $this
     */
    public function add(string $package, array $licenses): self
    {
        $this->data[$package] = $licenses;

        return $this;
    }

    /**
     * Transform object to array.
     *
     * @return array<array>
     */
    public function toArray(): array
    {
        return  $this->data;
    }
}
