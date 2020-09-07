<?php

namespace Trafaret\Validator;

interface ConstraintValidatorInterface
{
    /**
     * Validates a value by expression.
     *
     * @throws \Trafaret\Exception\UnsupportedConstraintException
     * @throws \Trafaret\Exception\UnexpectedValueException
     */
    public function validate(string $constraint, string $value): void;
}
