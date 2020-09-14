<?php

namespace Trafaret\Exception;

final class UnexpectedLineException extends \RuntimeException implements ExceptionInterface
{
    private $expectedLine;
    private $actualLine;

    public function __construct(string $expected_line, string $actual_line)
    {
        $this->expectedLine = $expected_line;
        $this->actualLine = $actual_line;

        if (!$actual_line === '') {
            $message = \sprintf('Line "%s" was not found.', $expected_line);
        }
        elseif (!$expected_line === '') {
            $message = \sprintf('Line "%s" was not expectedLine.', $actual_line);
        }
        else {
            $message = \sprintf('Expected line "%s" does not match "%s".', $expected_line, $actual_line);
        }
        parent::__construct($message);
    }

    public function getExpectedLine(): string
    {
        return $this->expectedLine;
    }

    public function getGetActualLine(): string
    {
        return $this->actualLine;
    }
}
