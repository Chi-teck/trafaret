<?php

declare(strict_types=1);

namespace Trafaret\Tests\Excpetion;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Validation;
use Trafaret\Exception\UnexpectedValueException;

final class UnexpectedValueExceptionTest extends TestCase
{
    public function testUnexpectedLineException(): void
    {
        $exception = new UnexpectedValueException(
            'aaa {{ bbb }} ccc',
            'aaa 10 ccc',
            Validation::createValidator()->validate(10, new GreaterThan(15))[0],
            'bbb',
        );

        $expected_message = <<< 'TEXT'
            Unexpected value "10" for "bbb" variable.
            This value should be greater than 15.
            Trafaret line: aaa {{ bbb }} ccc
               Input line: aaa 10 ccc
            TEXT;

        self::assertSame($exception->getMessage(), $expected_message);
    }
}
