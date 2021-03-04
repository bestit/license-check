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
                false,
            ],
            [
                __DIR__ . '/../fixtures/configuration/config2.yml',
                __DIR__ . '/../fixtures/composer/fixture1/',
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
     * @param bool $valid
     *
     * @return void
     */
    public function test(string $configuration, string $directory, bool $valid): void
    {
        $command = sprintf(
            __DIR__ . '/../../bin/license-check %s --configuration %s',
            $directory,
            $configuration,
        );

        $output = null;
        $resultCode = null;

        exec($command, $output, $resultCode);

        self::assertEquals($valid, $resultCode === 0);
    }
}
