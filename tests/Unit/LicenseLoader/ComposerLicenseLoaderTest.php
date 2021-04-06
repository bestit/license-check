<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck\LicenseLoader;

use ArrayIterator;
use BestIt\LicenseCheck\LicenseLoader\Exception\LicenseLoaderException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use function file_get_contents;

/**
 * Tests for the class ComposerLicenseLoader.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck\LicenseLoader
 */
class ComposerLicenseLoaderTest extends TestCase
{
    /**
     * The test fixture.
     *
     * @var ComposerLicenseLoader $fixture
     */
    private ComposerLicenseLoader $fixture;

    /**
     * @var MockObject|Finder
     */
    private MockObject|Finder $finder;

    /**
     * Set up the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->fixture = new ComposerLicenseLoader(
            $this->finder = $this->createMock(Finder::class),
        );
    }

    /**
     * Test to get licenses.
     *
     * @return void
     */
    public function testGetLicenses(): void
    {
        $this
            ->finder
            ->method('name')
            ->with('composer.lock')
            ->willReturnSelf();

        $this
            ->finder
            ->method('in')
            ->with($path = '/directory')
            ->willReturnSelf();

        $iterator = new ArrayIterator([
            $file1 = $this->createMock(SplFileInfo::class),
            $file2 = $this->createMock(SplFileInfo::class),
        ]);

        $this
            ->finder
            ->method('getIterator')
            ->willReturn($iterator);

        $file1
            ->method('getContents')
            ->willReturn(file_get_contents(__DIR__ . '/../../fixtures/composer/fixture1/composer.lock'));

        $file2
            ->method('getContents')
            ->willReturn(file_get_contents(__DIR__ . '/../../fixtures/composer/fixture2/composer.lock'));

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
            $this->fixture->getLicenses($path),
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
            ->finder
            ->method('name')
            ->with('composer.lock')
            ->willReturnSelf();

        $this
            ->finder
            ->method('in')
            ->with($path = '/directory')
            ->willReturnSelf();

        $this
            ->finder
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

        $this->expectException(LicenseLoaderException::class);
        $this->expectExceptionMessage(sprintf('Cannot decode content of file PATH'));

        $this->fixture->getLicenses($path);
    }

    /**
     * Test to get licenses.
     *
     * @return void
     */
    public function testGetLicensesWithInaccessibleFile(): void
    {
        $this
            ->finder
            ->method('name')
            ->with('composer.lock')
            ->willReturnSelf();

        $this
            ->finder
            ->method('in')
            ->with($path = '/directory')
            ->willReturnSelf();

        $this
            ->finder
            ->method('getIterator')
            ->willReturn(new ArrayIterator([
                $file = $this->createMock(SplFileInfo::class),
            ]));

        $file
            ->method('getPathname')
            ->willReturn('PATH');

        $file
            ->method('getContents')
            ->willReturn(false);

        $this->expectException(LicenseLoaderException::class);
        $this->expectExceptionMessage(sprintf('Cannot read content of file PATH'));

        $this->fixture->getLicenses($path);
    }

    /**
     * Test that loader returns the correct name.
     *
     * @return void
     */
    public function testGetName(): void
    {
        self::assertEquals('composer', $this->fixture->getName());
    }

    /**
     * Test that loader returns the correct name.
     *
     * @return void
     */
    public function testInheritance(): void
    {
        self::assertInstanceOf(LicenseLoaderInterface::class, $this->fixture);
    }
}
