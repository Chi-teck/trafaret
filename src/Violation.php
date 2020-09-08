<?php

namespace Trafaret;

/**
 * A data structure to represent a single violation.
 *
 * @internal
 */
class Violation
{
    private $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function __toString(): string
    {
        return $this->getMessage();
    }
}
