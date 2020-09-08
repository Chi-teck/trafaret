<?php

namespace Trafaret\Tests\Validator;

use Trafaret\Exception\UnsupportedConstraintException as UCE;
use Trafaret\Constraint\Any;

final class AnyTest extends AbstractValidatorTest
{
    protected $class = Any::class;

    public static function dataProvider(): array
    {
        return [
            ['any', '123'],
            [' any ', 'abc', new UCE()],
            ['matches', 'xxx', new UCE()],
        ];
    }
}
