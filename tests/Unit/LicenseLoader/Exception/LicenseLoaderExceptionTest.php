<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck\LicenseLoader\Exception;

use BestIt\LicenseCheck\Exception\LicenseCheckException;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the class LicenseLoaderException.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck\LicenseLoader\Exception
 */
class LicenseLoaderExceptionTest extends TestCase
{
    /**
     * The test fixture.
     *
     * @var LicenseLoaderException $fixture
     */
    private LicenseLoaderException $fixture;

    /**
     * Set up the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->fixture = new LicenseLoaderException();
    }

    /**
     * Test the inheritance of the exception.
     *
     * @return void
     */
    public function testInheritance(): void
    {
        self::assertInstanceOf(LicenseCheckException::class, $this->fixture);
    }
}
