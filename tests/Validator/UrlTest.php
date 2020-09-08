<?php

namespace Trafaret\Tests\Validator;

use Trafaret\Exception\UnexpectedValueException as UVE;
use Trafaret\Exception\UnsupportedConstraintException as UCE;
use Trafaret\Constraint\Url;

final class UrlTest extends AbstractValidatorTest
{
    protected $class = Url::class;

    public static function dataProvider(): array
    {
        return [
            ['abc', 'https://example.com', new UCE()],
            ['url', 'wrong-url', new UVE('The value "wrong-url" is not a valid URL.')],
            ['url', 'https://example.com'],
        ];
    }
}
