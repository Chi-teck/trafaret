<?php

namespace Trafaret\Exception;

use Symfony\Component\Validator\ConstraintViolationInterface;

final class UnexpectedValueException extends \RuntimeException implements ExceptionInterface
{
    public function __construct(
        string $trafaret_line,
        string $input_line,
        ConstraintViolationInterface $violation,
        string $name
    ) {
        $message = \sprintf('Unexpected value "%s" for "%s" variable.', $violation->getInvalidValue(), $name) . "\n";
        $message .= $violation->getMessage() . "\n";
        $message .= 'Trafaret line: ' . $trafaret_line . "\n";
        $message .= '   Input line: ' . $input_line;
        parent::__construct($message);
    }
}
