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
     * Check if the given package is allowed.
     *
     * @param string $package
     * @param string[] $allowedPackages
     *
     * @return bool
     */
    private function isPackageAllowed(string $package, array $allowedPackages): bool
    {
        $allowed = false;

        foreach ($allowedPackages as $allowedPackage) {
            if (preg_match($allowedPackage, $package) === 1) {
                $allowed = true;
                break;
            }
        }

        return $allowed;
    }

    /**
     * Start the validation.
     *
     * @param Configuration $configuration
     * @param string $path
     * @param int|null $depth
     *
     * @return Result
     */
    public function validate(Configuration $configuration, string $path, ?int $depth = null): Result
    {
        $result = new Result();

        $allowedLicenses = $configuration->getAllowedLicenses();
        foreach ($this->loaders as $loader) {
            $type = $loader->getName();
            $allowedPackages = $configuration->getAllowedPackages($type);
            foreach ($loader->getLicenses($path, $depth) as $package => $licenses) {
                if (!$this->isPackageAllowed($package, $allowedPackages)) {
                    if (count($licenses) === 0) {
                        $result->addViolation(
                            sprintf(
                                'No license found for %s package %s.',
                                $type,
                                $package,
                            ),
                        );

                        continue;
                    }

                    $hasValidLicense = false;
                    foreach ($licenses as $license) {
                        if (in_array($license, $allowedLicenses)) {
                            $hasValidLicense = true;
                            break;
                        }
                    }

                    if (!$hasValidLicense) {
                        $result->addViolation(
                            sprintf(
                                'License (%s) of %s package %s is not allowed.',
                                implode(', ', $licenses),
                                $type,
                                $package,
                            ),
                        );
                    }
                }
            }
        }

        return $result;
    }
}
