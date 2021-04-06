<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck\LicenseLoader;

use BestIt\LicenseCheck\LicenseLoader\Exception\LicenseLoaderException;
use BestIt\LicenseCheck\Unit\LicenseLoader\LicenseLoaderTestCase;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use function sprintf;

class LicenseLoaderAbstractTest extends LicenseLoaderTestCase
{
    protected function setUp(): void
    {
        $this->testedObject = $this->getMockForAbstractClass(
            LicenseLoaderAbstract::class,
            [
                $this->finderMock = $this->createMock(Finder::class),
            ]
        );
    }

    public function testGetLicensesDelegation(): void
    {
        $this->mockFileIterator(
            [
                $file1 = $this->createMock(SplFileInfo::class),
                $file2 = $this->createMock(SplFileInfo::class),
            ],
            $path = '/directory',
        );

        $this->testedObject
            ->expects(static::once())
            ->method('getFilePattern')
            ->willReturn(static::USED_FILE_PATTERN);

        $this->testedObject
            ->expects(static::exactly(2))
            ->method('getPackagesLicensesOfFile')
            ->withConsecutive(
                [$file1],
                [$file2],
            )
            ->willReturnOnConsecutiveCalls(['1' => 'foo'], ['2' => 'bar']);

        $file1
            ->method('getPathname')
            ->willReturn('PATH');

        $file1
            ->method('isReadable')
            ->willReturn(true);

        $file2
            ->method('getPathname')
            ->willReturn('PATH');

        $file2
            ->method('isReadable')
            ->willReturn(true);

        static::assertSame(
            ['1' => 'foo', '2' => 'bar'],
            $this->testedObject->getLicenses($path),
        );
    }

    /**
     * Test to get licenses.
     *
     * @return void
     */
    public function testGetLicensesWithInaccessibleFile(): void
    {
        $this->testedObject
            ->expects(static::once())
            ->method('getFilePattern')
            ->willReturn(static::USED_FILE_PATTERN);

        $this->mockFileIterator(
            [$file = $this->createMock(SplFileInfo::class)],
            $path = '/directory',
        );

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
}