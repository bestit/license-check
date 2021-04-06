<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck\Unit\LicenseLoader;

use ArrayIterator;
use BestIt\LicenseCheck\LicenseLoader\LicenseLoaderInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

class LicenseLoaderTestCase extends TestCase
{
    protected const USED_FILE_PATTERN = 'TODO';

    protected const TEST_CLASS = 'TODO';

    protected MockObject|Finder $finderMock;

    protected MockObject|LicenseLoaderInterface $testedObject;

    /**
     * @return string
     */
    protected function getFilePattern(): string
    {
        // Maybe an abstract to force an api would be a nice idea, but I think this forcing is not a good idea in unit tests.
        return static::USED_FILE_PATTERN;
    }

    protected function getTestedClassName(): string
    {
        // Maybe an abstract to force an api would be a nice idea, but I think this forcing is not a good idea in unit tests.
        return static::TEST_CLASS;
    }

    protected function mockFileIterator(array $files, string $path): void
    {
        $this
            ->finderMock
            ->method('name')
            ->with($this->getFilePattern())
            ->willReturnSelf();

        $this
            ->finderMock
            ->method('in')
            ->with($path)
            ->willReturnSelf();

        $this
            ->finderMock
            ->method('getIterator')
            ->willReturn(new ArrayIterator($files));
    }

    protected function setUp(): void
    {
        $class = $this->getTestedClassName();
        $this->testedObject = new $class(
            $this->finderMock = $this->createMock(Finder::class),
        );
    }

    public function testInstance(): void
    {
        static::assertInstanceOf(LicenseLoaderInterface::class, $this->testedObject);
    }
}