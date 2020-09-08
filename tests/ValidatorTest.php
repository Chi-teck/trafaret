<?php

namespace Trafaret\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Trafaret\Config;
use Trafaret\Trafaret;
use Trafaret\Validator;
use Trafaret\Violation;
use Trafaret\ViolationList;

final class ValidatorTest extends TestCase
{
    /**
     * @dataProvider validatorDataProvider()
     */
    public function testValidator(Config $config, string $input, string $template, ViolationList $expected_violations): void
    {
        $validator = new Validator(new ExpressionLanguage(), $config);
        $violations = $validator->validate($input, new Trafaret($template));
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
            '<div>{% value matches "/^abc$/" %}</div>',
            self::createViolationList(),
        ];

        $data[] = [
            self::createConfig(true, false, true),
            '<div>wrong</div>',
            '<div>{% value matches "/abc/" %}</div>',
            self::createViolationList(
                new Violation('The value «wrong» does not satisfy the «value matches "/abc/"» expression.'),
            ),
        ];

        $data[] = [
            self::createConfig(true, false, true),
            '<div>123</div><div>456</div>',
            '<div>{% value == 123 %}</div><div>{% value == 456 %}</div>',
            self::createViolationList(),
        ];

        $data[] = [
            self::createConfig(true, false, true),
            '<div>abc</div> <span>extra</span>',
            '<div>{% value == "abc" %}</div>',
            self::createViolationList(
                new Violation(
                    'Expected line "<div>{% value == "abc" %}</div>" does not match "<div>abc</div> <span>extra</span>".',
                ),
            ),
        ];

        $data[] = [
            self::createConfig(true, false, true),
            <<< 'HTML'
                Line 1
                Line 2
            HTML,
            <<< 'HTML'
                Line start
                Line {% value > 10 %}
                Line end
            HTML,
            self::createViolationList(
                new Violation('Expected line "Line start" does not match "Line 1".'),
                new Violation('The value «2» does not satisfy the «value > 10» expression.'),
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
