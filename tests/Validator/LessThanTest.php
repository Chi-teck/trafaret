<?php

namespace Trafaret\Tests\Validator;

use Trafaret\Exception\UnexpectedValueException as UVE;
use Trafaret\Exception\UnsupportedConstraintException as UCE;
use Trafaret\Validator\LessThan;

final class LessThanTest extends AbstractValidatorTest
{
    protected $class = LessThan::class;

    public static function dataProvider(): array
    {
        return [
            ['< 10', '-5'],
            ['< 10', '0', ],
            ['< 10', '5'],
            ['< 10', '10', new UVE('Expected a number less than 10. Got: 10.')],
            ['< -10', '-3', new UVE('Expected a number less than -10. Got: -3.')],
            ['< 571.56', '571.25'],
            ['< 571.56', '571.75', new UVE('Expected a number less than 571.56. Got: 571.75.')],
            [' any ', 'abc', new UCE()],
            ['matches', 'xxx', new UCE()],
        ];
    }
}
