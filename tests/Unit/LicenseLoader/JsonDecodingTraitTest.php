<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck\Unit\LicenseLoader;

use BestIt\LicenseCheck\LicenseLoader\Exception\LicenseLoaderException;
use BestIt\LicenseCheck\LicenseLoader\JsonDecodingTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\SplFileInfo;
use function json_encode;
use function sprintf;
use function uniqid;

class JsonDecodingTraitTest extends TestCase
{
    private object $testedObject;

    protected function setUp(): void
    {
        $this->testedObject = new class {
            use JsonDecodingTrait;

            public function getLicencesOfFile(SplFileInfo $file): array
            {
                return $this->getPackagesLicensesOfFile($file);
            }

            private function parseLibraryFileArray(array $rawData): array
            {
                return $rawData;
            }
        };
    }

    public function testGetPackagesLicensesOfFileFail(): void
    {
        $this->expectException(LicenseLoaderException::class);
        $this->expectExceptionMessage(sprintf('Cannot decode content of file %s', $path = uniqid()));

        $file = $this->createMock(SplFileInfo::class);

        $file
            ->expects(static::once())
            ->method('getContents')
            ->willReturn('');

        $file
            ->expects(static::once())
            ->method('getPathname')
            ->willReturn($path);

        $this->testedObject->getLicencesOfFile($file);
    }

    public function testGetPackagesLicensesOfFileSuccess(): void
    {
        $file = $this->createMock(SplFileInfo::class);

        $file
            ->expects(static::once())
            ->method('getContents')
            ->willReturn(json_encode($array = ['foo' => 'bar']));

        static::assertSame($array, $this->testedObject->getLicencesOfFile($file));
    }
}