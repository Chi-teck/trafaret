<?php

namespace Trafaret\Exception;

use Symfony\Component\Validator\ConstraintViolation;

final class UnexpectedValueException extends \RuntimeException implements ExceptionInterface
{
    public function __construct(string $expected_line, string $actual_line, ConstraintViolation $violation, string $name)
    {
        $message = \sprintf('Unexpected value "%s" for "%s" variable.', $violation->getInvalidValue(), $name) . "\n";
        $message .= $violation->getMessage() . "\n";
        $message .= 'Trafaret line: ' . $expected_line . "\n";
        $message .= '   Input line: ' . $actual_line;
        parent::__construct($message);
    }
}
