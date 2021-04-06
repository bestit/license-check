<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck\LicenseLoader;

/**
 * Interface for license loader for different types. E.g. composer, npm or something else.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck\LicenseLoader
 */
interface LicenseLoaderInterface
{
    /**
     * Get used licenses in the specified path.
     *
     * @param string $path Search path.
     * @param int|null $depth (Optional) Search depth.
     *
     * @return array<array<string>>
     */
    public function getLicenses(string $path, ?int $depth = null): array;

    /**
     * Get name of the license loader.
     *
     * @return string
     */
    public function getName(): string;
}
