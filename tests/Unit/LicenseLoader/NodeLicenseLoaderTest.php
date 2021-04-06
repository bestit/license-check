<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck\LicenseLoader;

use ArrayIterator;
use BestIt\LicenseCheck\LicenseLoader\Exception\LicenseLoaderException;
use BestIt\LicenseCheck\Unit\LicenseLoader\LicenseLoaderTestCase;
use Symfony\Component\Finder\SplFileInfo;
use function file_get_contents;

/**
 * Tests for the class NodeLicenseLoader.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck\LicenseLoader
 */
class NodeLicenseLoaderTest extends LicenseLoaderTestCase
{
    protected const TEST_CLASS = NodeLicenseLoader::class;

    protected const USED_FILE_PATTERN = '/node_modules\/([A-Za-z0-9]|-|_)*\/package.json/';

    /**
     * Test to get licenses.
     *
     * @return void
     */
    public function testGetLicenses(): void
    {
        $this
            ->finderMock
            ->method('path')
            ->with('/node_modules\/([A-Za-z0-9]|-|_)*\/package.json/')
            ->willReturnSelf();

        $this
            ->finderMock
            ->method('in')
            ->with($path = '/directory')
            ->willReturnSelf();

        $iterator = new ArrayIterator([
            $file1 = $this->createMock(SplFileInfo::class),
            $file2 = $this->createMock(SplFileInfo::class),
            $file3 = $this->createMock(SplFileInfo::class),
        ]);

        $this
            ->finderMock
            ->method('getIterator')
            ->willReturn($iterator);

        $file1
            ->method('getContents')
            ->willReturn(file_get_contents(__DIR__ . '/../../fixtures/node/fixture1/node_modules/a/package.json'));

        $file1
            ->method('isReadable')
            ->willReturn(true);

        $file2
            ->method('getContents')
            ->willReturn(file_get_contents(__DIR__ . '/../../fixtures/node/fixture1/node_modules/b/package.json'));

        $file2
            ->method('isReadable')
            ->willReturn(true);

        $file3
            ->method('getContents')
            ->willReturn(file_get_contents(__DIR__ . '/../../fixtures/node/fixture1/node_modules/c/package.json'));

        $file3
            ->method('isReadable')
            ->willReturn(true);

        self::assertEquals(
            [
                'a' => ['ISC'],
                'b' => ['MIT'],
                'c' => ['BSD'],
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
        $this
            ->finderMock
            ->method('path')
            ->with('/node_modules\/([A-Za-z0-9]|-|_)*\/package.json/')
            ->willReturnSelf();

        $this
            ->finderMock
            ->method('in')
            ->with($path = '/directory')
            ->willReturnSelf();

        $this
            ->finderMock
            ->method('getIterator')
            ->willReturn(new ArrayIterator([
                $file = $this->createMock(SplFileInfo::class),
            ]));

        $file
            ->method('getPathname')
            ->willReturn('PATH');

        $file
            ->method('getContents')
            ->willReturn('');

        $file
            ->method('isReadable')
            ->willReturn(true);

        $this->expectException(LicenseLoaderException::class);
        $this->expectExceptionMessage(sprintf('Cannot decode content of file PATH'));

        $this->testedObject->getLicenses($path);
    }

    /**
     * Test to get licenses.
     *
     * @return void
     */
    public function testGetLicensesWithInaccessibleFile(): void
    {
        $this
            ->finderMock
            ->method('path')
            ->with('/node_modules\/([A-Za-z0-9]|-|_)*\/package.json/')
            ->willReturnSelf();

        $this
            ->finderMock
            ->method('in')
            ->with($path = '/directory')
            ->willReturnSelf();

        $this
            ->finderMock
            ->method('getIterator')
            ->willReturn(new ArrayIterator([
                $file = $this->createMock(SplFileInfo::class),
            ]));

        $file
            ->method('getPathname')
            ->willReturn('PATH');

        $file
            ->method('isReadable')
            ->willReturn(false);

        $this->expectException(LicenseLoaderException::class);
        $this->expectExceptionMessage(sprintf('Cannot read content of file PATH'));

        $this->testedObject->getLicenses($path);
    }

    /**
     * Test that loader returns the correct name.
     *
     * @return void
     */
    public function testGetName(): void
    {
        self::assertEquals('node', $this->testedObject->getName());
    }
}
