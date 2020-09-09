<?php

namespace Trafaret\Tests;

use PHPUnit\Framework\TestCase;
use Trafaret\ExpressionFunctionProvider;

final class ExpressionFunctionProviderTest extends TestCase
{
    /**
     * @dataProvider functionDataProvider
     */
    public function testFunction(int $index, string $name, ?string $compiled, array $data = []): void
    {
        $function = (new ExpressionFunctionProvider())->getFunctions()[$index];
        self::assertSame($name, $function->getName());

        $evaluator = $function->getEvaluator();
        foreach ($data as $input => $result) {
            self::assertSame($result, $evaluator([], $input));
        }

        if ($compiled === null) {
            $this->expectException(\LogicException::class);
            $this->expectExceptionMessage('Compilation is not supported.');
        }
        self::assertSame($compiled, $function->getCompiler()());
    }

    public static function functionDataProvider(): array
    {
        $index = 0;

        $data[] = [
            $index++,
            'is_empty',
            null,
            [
                '123' => false,
                '' => true,
            ],
        ];

        $data[] = [
            $index++,
            'is_email',
            null,
            [
                'wrong email' => false,
                'no-reply@example.com' => true,
            ],
        ];

        $data[] = [
            $index++,
            'is_url',
            null,
            [
                'wrong url' => false,
                'https://example.com' => true,
            ]
        ];

        $data[] = [
            $index++,
            'is_integer',
            null,
            [
                '123' => true,
                'aaa' => false,
                '123.56' => false,
                '-10' => true,
                '0' => true,
                'a1' => false,
                '1a' => false,
            ]
        ];

        $data[] = [$index++, 'is_numeric', '\is_numeric()'];
        $data[] = [$index++, 'ctype_alnum', '\ctype_alnum()'];
        $data[] = [$index++, 'ctype_alpha', '\ctype_alpha()'];
        $data[] = [$index++, 'ctype_cntrl', '\ctype_cntrl()'];
        $data[] = [$index++, 'ctype_graph', '\ctype_graph()'];
        $data[] = [$index++, 'ctype_lower', '\ctype_lower()'];
        $data[] = [$index++, 'ctype_print', '\ctype_print()'];
        $data[] = [$index++, 'ctype_punct', '\ctype_punct()'];
        $data[] = [$index++, 'ctype_space', '\ctype_space()'];
        $data[] = [$index, 'ctype_xdigit', '\ctype_xdigit()'];

        return $data;
    }
}
