<?php

namespace Trafaret\Validator;

use Trafaret\Exception\UnexpectedValueException;
use Trafaret\Exception\UnsupportedConstraintException;

final class LessThan implements ConstraintValidatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function validate(string $constraint, string $value): void
    {
        if (!\preg_match('/^< (?P<limit>-?\d+(\.\d+)?)$/i', $constraint, $matches)) {
            throw new UnsupportedConstraintException();
        }
        if (!\is_numeric($value) || $value >= $matches['limit']) {
            $message = \sprintf('Expected a number less than %s. Got: %s.', $matches['limit'], $value);
            throw new UnexpectedValueException($message);
        }
    }
}
