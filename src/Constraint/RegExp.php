<?php

namespace Trafaret\Constraint;

use Trafaret\Exception\UnexpectedValueException;
use Trafaret\Exception\UnsupportedConstraintException;

final class RegExp implements ConstraintInterface
{
    /**
     * {@inheritdoc}
     */
    public function validate(string $constraint, string $value): void
    {
        if (!\preg_match('/^matches\s+(?P<pattern>[^\s]+)$/i', $constraint, $matches)) {
            throw new UnsupportedConstraintException();
        }
        if (!\preg_match($matches['pattern'], $value)) {
            $message = \sprintf('The value "%s" does not match the pattern "%s".', $value, $matches['pattern']);
            throw new UnexpectedValueException($message);
        }
    }
}
