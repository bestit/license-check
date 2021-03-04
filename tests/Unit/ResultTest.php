<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck;

use PHPUnit\Framework\TestCase;

/**
 * Tests for the class Result.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck
 */
class ResultTest extends TestCase
{
    /**
     * The test fixture.
     *
     * @var Result $fixture
     */
    private Result $fixture;

    /**
     * Set up the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->fixture = new Result();
    }

    /**
     * Test that the result set works correct.
     *
     * @return void
     */
    public function testResult(): void
    {
        self::assertTrue($this->fixture->isPassed());

        $this->fixture->addViolation('Violation1');
        self::assertFalse($this->fixture->isPassed());

        $this->fixture->addViolation('Violation2');
        self::assertFalse($this->fixture->isPassed());

        $this->fixture->addViolation('Violation3');
        self::assertFalse($this->fixture->isPassed());

        self::assertEquals(
            ['Violation1', 'Violation2', 'Violation3'],
            $this->fixture->getViolations(),
        );
    }
}
