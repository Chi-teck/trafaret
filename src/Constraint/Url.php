<?php

namespace Trafaret\Constraint;

use Trafaret\Exception\UnexpectedValueException;
use Trafaret\Exception\UnsupportedConstraintException;

final class Url implements ConstraintInterface
{
    /**
     * {@inheritdoc}
     */
    public function validate(string $constraint, string $value): void
    {
        if ($constraint != 'url') {
            throw new UnsupportedConstraintException();
        }
        if (!\filter_var($value, \FILTER_VALIDATE_URL) !== false) {
            $message = \sprintf('The value "%s" is not a valid URL.', $value);
            throw new UnexpectedValueException($message);
        }
    }
}
