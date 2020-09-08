<?php

namespace Trafaret;

/**
 * A data structure to represent a single violation.
 */
final class ComparableViolation extends Violation
{
    private $expected;
    private $actual;

    public function __construct(string $message, string $expected, string $actual)
    {
        parent::__construct($message);
        $this->expected = $expected;
        $this->actual = $actual;
    }

    public function getExpected(): string
    {
        return $this->expected;
    }

    public function getGetActual(): string
    {
        return $this->actual;
    }
}
