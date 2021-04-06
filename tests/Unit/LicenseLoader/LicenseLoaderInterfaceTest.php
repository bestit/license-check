<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck\LicenseLoader;

use PHPUnit\Framework\TestCase;
use ReflectionObject;

/**
 * Tests for the class LicenseLoaderInterface.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck\LicenseLoader
 */
class LicenseLoaderInterfaceTest extends TestCase
{
    /**
     * The test fixture.
     *
     * @var LicenseLoaderInterface $fixture
     */
    private LicenseLoaderInterface $fixture;

    /**
     * Set up the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->fixture = $this->createMock(LicenseLoaderInterface::class);
    }

    /**
     * Test the inheritance of the exception.
     *
     * @return void
     */
    public function testDefinition(): void
    {
        $reflection = new ReflectionObject($this->fixture);

        self::assertTrue($reflection->hasMethod('getLicenses'));

        self::assertArrayHasKey('0', $reflection->getMethod('getLicenses')->getParameters());
        self::assertEquals('path', $reflection->getMethod('getLicenses')->getParameters()[0]->getName());
        self::assertEquals('string', (string) $reflection->getMethod('getLicenses')->getParameters()[0]->getType());
        self::assertEquals('array', (string) $reflection->getMethod('getLicenses')->getReturnType());

        self::assertArrayHasKey('1', $reflection->getMethod('getLicenses')->getParameters());
        self::assertEquals('depth', $reflection->getMethod('getLicenses')->getParameters()[1]->getName());
        self::assertEquals('?int', (string) $reflection->getMethod('getLicenses')->getParameters()[1]->getType());

        self::assertEquals('array', (string) $reflection->getMethod('getLicenses')->getReturnType());

        self::assertTrue($reflection->hasMethod('getName'));
        self::assertEquals('string', (string) $reflection->getMethod('getName')->getReturnType());
    }
}
