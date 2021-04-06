<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck\LicenseLoader;

use BestIt\LicenseCheck\LicenseLoader\Exception\LicenseLoaderException;
use BestIt\LicenseCheck\Unit\LicenseLoader\LicenseLoaderTestCase;
use Symfony\Component\Finder\SplFileInfo;
use function file_get_contents;

/**
 * Tests for the class ComposerLicenseLoader.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck\LicenseLoader
 */
class ComposerLicenseLoaderTest extends LicenseLoaderTestCase
{
    protected const TEST_CLASS = ComposerLicenseLoader::class;

    protected const USED_FILE_PATTERN = 'composer.lock';

    /**
     * Test to get licenses.
     *
     * @return void
     */
    public function testGetLicenses(): void
    {
        $files = [
            $file1 = $this->createMock(SplFileInfo::class),
            $file2 = $this->createMock(SplFileInfo::class),
        ];

        $this->mockFileIterator($files, $path = '/directory');

        $file1
            ->method('getContents')
            ->willReturn(file_get_contents(__DIR__ . '/../../fixtures/composer/fixture1/composer.lock'));

        $file1
            ->method('isReadable')
            ->willReturn(true);

        $file2
            ->method('getContents')
            ->willReturn(file_get_contents(__DIR__ . '/../../fixtures/composer/fixture2/composer.lock'));

        $file2
            ->method('isReadable')
            ->willReturn(true);

        self::assertEquals(
            [
                'vendorA/package1' => ['MIT'],
                'vendorA/package2' => ['Apache-2.0'],
                'vendorB/package1' => ['BSD'],
                'vendorB/package2' => ['BSD', 'MIT'],
                'vendorC/package1' => ['MIT'],
                'vendorC/package2' => ['Apache-2.0'],
                'vendorD/package1' => ['AGPL-3.0-only'],
            ],
            $this->testedObject->getLicenses($path),
        );
    }

    /**
     * Test to get licenses.
     *
     * @return void
     */
    public function testGetLicensesWithEmptyFile(): void
    {
        $files = [
            $file = $this->createMock(SplFileInfo::class),
        ];

        $this->mockFileIterator($files, $path = '/directory');

        $file
            ->method('getContents')
            ->willReturn('');

        $file
            ->method('getPathname')
            ->willReturn('PATH');

        $file
            ->method('isReadable')
            ->willReturn(true);

        $this->expectException(LicenseLoaderException::class);
        $this->expectExceptionMessage(sprintf('Cannot decode content of file PATH'));

        $this->testedObject->getLicenses($path);
    }

    /**
     * Test that loader returns the correct name.
     *
     * @return void
     */
    public function testGetName(): void
    {
        self::assertEquals('composer', $this->testedObject->getName());
    }
}
