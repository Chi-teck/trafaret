<?php

namespace Trafaret\Validator;

use Trafaret\Exception\UnsupportedConstraintException;

final class Any implements ConstraintValidatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function validate(string $constraint, string $value): void
    {
        if ($constraint != 'any') {
            throw new UnsupportedConstraintException();
        }
    }
}
