<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck;

use BestIt\LicenseCheck\Configuration\Configuration;
use BestIt\LicenseCheck\LicenseLoader\LicenseLoaderInterface;

/**
 * Running different loaders to check the licenses of different package managers.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck
 */
class Checker
{
    /**
     * @var array<LicenseLoaderInterface> $loaders Array of loaders for different package managers.
     */
    private iterable $loaders;

    /**
     * Create checker instance and register the loaders.
     *
     * @param LicenseLoaderInterface[] $loaders
     */
    public function __construct(iterable $loaders)
    {
        $this->loaders = $loaders;
    }

    /**
     * Start the validation.
     *
     * @param Configuration $configuration
     * @param string $path
     *
     * @return Result
     */
    public function validate(Configuration $configuration, string $path): Result
    {
        $result = new Result();

        $allowedLicenses = $configuration->getAllowedLicenses();
        foreach ($this->loaders as $loader) {
            $type = $loader->getName();
            $allowedPackages = $configuration->getAllowedPackages($type);
            foreach ($loader->getLicenses($path) as $package => $licenses) {
                if (!in_array($package, $allowedPackages)) {
                    if (count($licenses) === 0) {
                        $result->addViolation(
                            sprintf(
                                'No license found for %s package %s.',
                                $type,
                                $package,
                            ),
                        );
                    }

                    foreach ($licenses as $license) {
                        if (!in_array($license, $allowedLicenses)) {
                            $result->addViolation(
                                sprintf(
                                    'License (%s) of %s package %s is not allowed.',
                                    $license,
                                    $type,
                                    $package,
                                ),
                            );
                        }
                    }
                }
            }
        }

        return $result;
    }
}
