<?php

namespace Trafaret\Tests\Validator;

use Trafaret\Exception\UnexpectedValueException as UVE;
use Trafaret\Exception\UnsupportedConstraintException as UCE;
use Trafaret\Constraint\RegExp;

final class RegExpTest extends AbstractValidatorTest
{
    protected $class = RegExp::class;

    public static function dataProvider(): array
    {
        return [
            ['matches /^abc$/', 'abc'],
            ['matches #^\d{3}$#', '123'],
            ['matches    /^abc$/', 'abc'],
            ['matches/^abc$/', 'abc', new UCE()],
            ['matches /^abs$/', 'xxx', new UVE('The value "xxx" does not match the pattern "/^abs$/".')],
            ['zzz /123/', '123', new UCE()],
            [' matches xxx', '123', new UCE()],
        ];
    }
}
