<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck;

use ArrayIterator;
use BestIt\LicenseCheck\Configuration\ConfigurationLoader;
use BestIt\LicenseCheck\LicenseLoader\LicenseLoaderInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the class Checker.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck
 */
class CheckerTest extends TestCase
{
    /**
     * The test fixture.
     *
     * @var Checker $fixture
     */
    private Checker $fixture;

    /**
     * Dummy loader 1.
     *
     * @var MockObject|LicenseLoaderInterface $loader1
     */
    private MockObject|LicenseLoaderInterface $loader1;

    /**
     * Dummy loader 2.
     *
     * @var MockObject|LicenseLoaderInterface $loader2
     */
    private MockObject|LicenseLoaderInterface $loader2;

    /**
     * Set up the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->fixture = new Checker(
            new ArrayIterator([
                $this->loader1 = $this->createMock(LicenseLoaderInterface::class),
                $this->loader2 = $this->createMock(LicenseLoaderInterface::class),
            ]),
        );
    }

    /**
     * Test that checker validates licenses correctly.
     *
     * @return void
     */
    public function testValidate(): void
    {
        $path = '/test-directory';

        $this
            ->loader1
            ->method('getName')
            ->willReturn($name1 = 'Name1');

        $this
            ->loader1
            ->method('getLicenses')
            ->with($path)
            ->willReturn([
                'vendorA/package1' => [
                    'MIT',
                ],
                'vendorA/package2' => [
                    'BSD',
                ],
                'vendorB/package1' => [
                    'Apache-2.0',
                ],
                'vendorC/package1' => [
                    'Apache-3.0',
                ],
            ]);

        $this
            ->loader2
            ->method('getName')
            ->willReturn($name2 = 'Name2');

        $this
            ->loader2
            ->method('getLicenses')
            ->with($path)
            ->willReturn([
                'vendorD/package1' => [
                    'MIT',
                ],
                'vendorE/package1' => [
                    'BSD',
                ],
                'vendorF/package1' => [
                    'Apache-2.0',
                ],
            ]);

        $configuration = (new ConfigurationLoader())->load(__DIR__ . '/../fixtures/configuration/config1.yml');

        $result = $this->fixture->validate($configuration, $path);

        self::assertEquals(
            [
                'License (Apache-2.0) of Name1 package vendorB/package1 is not allowed.',
                'License (Apache-3.0) of Name1 package vendorC/package1 is not allowed.',
                'License (Apache-2.0) of Name2 package vendorF/package1 is not allowed.',
            ],
            $result->getViolations(),
        );
    }
}
