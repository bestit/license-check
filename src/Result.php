<?php

declare(strict_types=1);

namespace BestIt\LicenseCheck;

/**
 * Result of a checker run.
 *
 * @author best it AG <info@bestit.de>
 * @package BestIt\LicenseCheck
 */
class Result
{
    /**
     * Array of detected violations.
     *
     * @var array<string> $violations
     */
    private array $violations = [];

    /**
     * Add a violation to the result.
     *
     * @param string $violation
     *
     * @return void
     */
    public function addViolation(string $violation): void
    {
        $this->violations[] = $violation;
    }

    /**
     * Get the violations of the result.
     *
     * @return array<string>
     */
    public function getViolations(): array
    {
        return $this->violations;
    }

    /**
     * Check if the result contains any violations.
     *
     * @return bool
     */
    public function isPassed(): bool
    {
        return count($this->violations) === 0;
    }
}
