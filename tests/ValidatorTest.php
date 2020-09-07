<?php

namespace Trafaret\Tests;

use PHPUnit\Framework\TestCase;
use Trafaret\Config;
use Trafaret\Validator;
use Trafaret\Validator\ConstraintValidatorList;
use Trafaret\Violation;
use Trafaret\ViolationList;

final class ValidatorTest extends TestCase
{
    /**
     * @dataProvider validatorDataProvider()
     */
    public function testValidator(Config $config, string $input, string $trafaret, ViolationList $expected_violations): void
    {
        $validator = new Validator($config, ConstraintValidatorList::createDefault());
        $violations = $validator->validate($input, $trafaret);
        self::assertEquals($expected_violations, $violations);
    }

    public static function validatorDataProvider(): array
    {
        // -- Default case.
        $data[] = [
            self::createConfig(true, false, true),
            '<div>Test</div>',
            '<div>Test</div>',
            self::createViolationList(),
        ];

        // -- Leading spaces.
        $data[] = [
            self::createConfig(true, false, true),
            '   <div>Test</div>',
            '<div>Test</div>',
            self::createViolationList(),
        ];

        // --
        $data[] = [
            self::createConfig(true, false, true),
            '<div>abc</div>',
            '<div>{% matches /^abc$/ %}</div>',
            self::createViolationList(),
        ];

        $data[] = [
            self::createConfig(true, false, true),
            '<div>wrong</div>',
            '<div>{% matches /abc/ %}</div>',
            self::createViolationList(
                new Violation('The value "wrong" does not match the pattern "/abc/".'),
            ),
        ];

        $data[] = [
            self::createConfig(true, false, true),
            '<div>abc</div><div>xyz</div>',
            '<div>{% matches /^abc$/ %}</div><div>{% matches /^xyz/ %}</div>',
            self::createViolationList(),
        ];

        $data[] = [
            self::createConfig(true, false, true),
            '<div>abc</div> <span>extra</span>',
            '<div>{% matches /^abc$/ %}</div>',
            self::createViolationList(
                new Violation(
                    'Expected line "<div>{% matches /^abc$/ %}</div>" does not match "<div>abc</div> <span>extra</span>".',
                ),
            ),
        ];

        $data[] = [
            self::createConfig(true, false, true),
            <<< 'HTML'
                Line 1
                Line 2
                Line 3
            HTML,
            <<< 'HTML'
                Line start
                Line {% > 10 %}
                Line {% matches /abc/ %}
                Line end
            HTML,
            self::createViolationList(
                new Violation('Expected line "Line start" does not match "Line 1".'),
                new Violation('Expected a number greater than 10. Got: 2.'),
                new Violation('The value "3" does not match the pattern "/abc/".'),
                new Violation('Line "Line end" was not found.'),
            )
        ];

        return $data;
    }

    private static function createViolationList(Violation ...$violations): ViolationList
    {
        $list = new ViolationList();
        foreach ($violations as $violation) {
            $list[] = $violation;
        }
        return $list;
    }

    private static function createConfig(
        bool $ignore_leading_spaces,
        bool $ignore_trailing_spaces,
        bool $ignore_empty_lines
    ): Config {
        $data = [
          'ignore_leading_spaces' => $ignore_leading_spaces,
          'ignore_trailing_spaces' => $ignore_trailing_spaces,
          'ignore_empty_lines' => $ignore_empty_lines,
        ];
        return new Config($data);
    }
}
