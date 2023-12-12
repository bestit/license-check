<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck;

use PHPUnit\Framework\TestCase;

/**
 * Test that application works correctly.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck
 */
class IntegrationTest extends TestCase
{
    /**
     * Get
     *
     * @return array[]
     */
    public function getTestDataProvider(): array
    {
        return [
            [
                __DIR__ . '/../fixtures/configuration/config1.yml',
                __DIR__ . '/../fixtures/composer/fixture1/',
                null,
                false,
            ],
            [
                __DIR__ . '/../fixtures/configuration/config2.yml',
                __DIR__ . '/../fixtures/composer/fixture1/',
                null,
                true,
            ],
            [
                __DIR__ . '/../fixtures/configuration/config3.yml',
                __DIR__ . '/../fixtures/node/fixture1/',
                null,
                true,
            ],
            [
                __DIR__ . '/../fixtures/configuration/config4.yml',
                __DIR__ . '/../fixtures/node/fixture1/',
                null,
                true,
            ],
            [
                __DIR__ . '/../fixtures/configuration/config3.yml',
                __DIR__ . '/../fixtures/node/fixture2/',
                null,
                false,
            ],
            [
                __DIR__ . '/../fixtures/configuration/config1.yml',
                __DIR__ . '/../fixtures/composer/fixture3/',
                null,
                false,
            ],
            [
                __DIR__ . '/../fixtures/configuration/config1.yml',
                __DIR__ . '/../fixtures/composer/fixture3/',
                0,
                true,
            ],
        ];
    }

    /**
     * Test command execution.
     *
     * @dataProvider getTestDataProvider
     *
     * @param string $configuration
     * @param string $directory
     * @param int|null $depth
     * @param bool $valid
     *
     * @return void
     */
    public function test(string $configuration, string $directory, ?int $depth, bool $valid): void
    {
        if ($depth === null) {
            $command = sprintf(
                __DIR__ . '/../../bin/license-check %s --configuration %s',
                $directory,
                $configuration,
            );
        } else {
            $command = sprintf(
                __DIR__ . '/../../bin/license-check %s --configuration %s --depth %d',
                $directory,
                $configuration,
                $depth,
            );
        }

        $output = null;
        $resultCode = null;

        exec($command, $output, $resultCode);

        self::assertEquals($valid, $resultCode === 0);
    }
}
