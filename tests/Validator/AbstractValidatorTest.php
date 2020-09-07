<?php

namespace Trafaret\Tests\Validator;

use PHPUnit\Framework\TestCase;
use Trafaret\Exception\ExceptionInterface;

abstract class AbstractValidatorTest extends TestCase
{
    protected $class;

    /**
     * @dataProvider dataProvider()
     */
    public function testValidator(string $constraint, string $value, ?ExceptionInterface $exception = null): void
    {
        if ($exception) {
            $this->expectException(\get_class($exception));
            $this->expectExceptionMessage($exception->getMessage());
        }
        (new $this->class())->validate($constraint, $value);
        self::assertTrue(true);
    }

    abstract public static function dataProvider(): array;
}
