<?php

namespace Trafaret\Tests;

use PHPUnit\Framework\TestCase;
use Trafaret\Exception\UnexpectedLineException;

final class UnexpectedLineExceptionTest extends TestCase
{
    /**
     * @dataProvider dataProvider()
     */
    public function testUnexpectedLineException(string $expected_line, string $actual_line, string $message): void
    {
        $exception = new UnexpectedLineException($expected_line, $actual_line);
        self::assertSame($exception->getMessage(), $message);
        self::assertSame($expected_line, $exception->getExpectedLine());
        self::assertSame($actual_line, $exception->getGetActualLine());
    }

    public static function dataProvider(): array
    {
        return [
            ['foo', 'bar', 'Expected line "foo" does not match "bar".'],
            ['foo', '', 'Expected line "foo" does not match "".'],
            ['', 'bar', 'Expected line "" does not match "bar".'],
        ];
    }
}
