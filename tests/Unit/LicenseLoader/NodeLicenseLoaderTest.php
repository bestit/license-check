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
 * Tests for the class NodeLicenseLoader.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck\LicenseLoader
 */
class NodeLicenseLoaderTest extends TestCase
{
    /**
     * The test fixture.
     *
     * @var NodeLicenseLoader $fixture
     */
    private NodeLicenseLoader $fixture;

    /**
     * @var MockObject|Finder
     */
    private MockObject | Finder $finder;

    /**
     * Set up the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->fixture = new NodeLicenseLoader(
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
            ->method('path')
            ->with('/node_modules\/([A-Za-z0-9]|-|_)*\/package.json/')
            ->willReturnSelf();

        $this
            ->finder
            ->method('in')
            ->with($path = '/directory')
            ->willReturnSelf();

        $this
            ->finder
            ->method('depth')
            ->with($depth = random_int(0, 100))
            ->willReturnSelf();

        $iterator = new ArrayIterator([
            $file1 = $this->createMock(SplFileInfo::class),
            $file2 = $this->createMock(SplFileInfo::class),
            $file3 = $this->createMock(SplFileInfo::class),
        ]);

        $this
            ->finder
            ->method('getIterator')
            ->willReturn($iterator);

        $file1
            ->method('getContents')
            ->willReturn(file_get_contents(__DIR__ . '/../../fixtures/node/fixture1/node_modules/a/package.json'));

        $file2
            ->method('getContents')
            ->willReturn(file_get_contents(__DIR__ . '/../../fixtures/node/fixture1/node_modules/b/package.json'));

        $file3
            ->method('getContents')
            ->willReturn(file_get_contents(__DIR__ . '/../../fixtures/node/fixture1/node_modules/c/package.json'));

        self::assertEquals(
            [
                'a' => ['ISC'],
                'b' => ['MIT'],
                'c' => ['BSD'],
            ],
            $this->fixture->getLicenses($path, $depth),
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
            ->method('path')
            ->with('/node_modules\/([A-Za-z0-9]|-|_)*\/package.json/')
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
            ->method('path')
            ->with('/node_modules\/([A-Za-z0-9]|-|_)*\/package.json/')
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
        self::assertEquals('node', $this->fixture->getName());
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
