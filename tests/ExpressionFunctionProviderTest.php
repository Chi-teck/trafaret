<?php

namespace Trafaret\Tests;

use PHPUnit\Framework\TestCase;
use Trafaret\ExpressionFunctionProvider;

final class ExpressionFunctionProviderTest extends TestCase
{
    /**
     * @dataProvider functionDataProvider
     */
    public function testFunction(int $index, string $name, array $data): void
    {
        $function = (new ExpressionFunctionProvider())->getFunctions()[$index];
        self::assertSame($name, $function->getName());

        $evaluator = $function->getEvaluator();
        foreach ($data as $input => $result) {
            self::assertSame($result, $evaluator([], $input));
        }

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Compilation is not supported.');
        $function->getCompiler()();
    }

    public static function functionDataProvider(): array
    {
        $data[] = [
            0,
            'is_email',
            [
                'wrong email' => false,
                'no-reply@example.com' => true,
            ]
        ];

        $data[] = [
            1,
            'is_url',
            [
                'wrong url' => false,
                'https://example.com' => true,
            ]
        ];

        $data[] = [
            2,
            'is_integer',
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

        return $data;
    }
}
