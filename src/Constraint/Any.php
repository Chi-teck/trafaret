<?php

namespace Trafaret\Constraint;

use Trafaret\Exception\UnsupportedConstraintException;

final class Any implements ConstraintInterface
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
