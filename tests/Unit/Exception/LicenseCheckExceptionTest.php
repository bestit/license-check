<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck\Exception;

use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the class LicenseCheckException.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck\Exception
 */
class LicenseCheckExceptionTest extends TestCase
{
    /**
     * The test fixture.
     *
     * @var LicenseCheckException $fixture
     */
    private LicenseCheckException $fixture;

    /**
     * Set up the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->fixture = new LicenseCheckException();
    }

    /**
     * Test the inheritance of the exception.
     *
     * @return void
     */
    public function testInheritance(): void
    {
        self::assertInstanceOf(Exception::class, $this->fixture);
    }
}
