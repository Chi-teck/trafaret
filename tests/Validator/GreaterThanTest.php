<?php

namespace Trafaret\Tests\Validator;

use Trafaret\Exception\UnexpectedValueException as UVE;
use Trafaret\Exception\UnsupportedConstraintException as UCE;
use Trafaret\Constraint\GreaterThan;

final class GreaterThanTest extends AbstractValidatorTest
{
    protected $class = GreaterThan::class;

    public static function dataProvider(): array
    {
        return [
            ['> 10', '-5', new UVE('Expected a number greater than 10. Got: -5.')],
            ['> 10', '0', new UVE('Expected a number greater than 10. Got: 0.')],
            ['> 10', '5', new UVE('Expected a number greater than 10. Got: 5.')],
            ['> 10', '10', new UVE('Expected a number greater than 10. Got: 10.')],
            ['> -10', '-3'],
            ['> 571.56', '571.25', new UVE('Expected a number greater than 571.56. Got: 571.25.')],
            ['> 571.56', '571.75'],
            [' any ', 'abc', new UCE()],
            ['matches', 'xxx', new UCE()],
        ];
    }
}
