<?php

namespace Trafaret\Exception;

use Symfony\Component\Validator\ConstraintViolation;

final class UnexpectedValueException extends \RuntimeException implements ExceptionInterface
{

    public function __construct(ConstraintViolation $violation, string $name)
    {
        $message = \sprintf('Unexpected value "%s" for "%s" variable.', $violation->getInvalidValue(), $name);
        $message .= "\n" . $violation->getMessage();
        parent::__construct($message);
    }
}
