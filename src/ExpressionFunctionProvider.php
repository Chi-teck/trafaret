<?php

namespace Trafaret;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

final class ExpressionFunctionProvider implements ExpressionFunctionProviderInterface
{
    public function getFunctions(): array
    {
        $compiler = static function (): void {
            throw new \LogicException('Compilation is not supported.');
        };

        $functions[] = new ExpressionFunction(
            'is_email',
            $compiler,
            static function (array $arguments, string $input): bool {
                return \filter_var($input, \FILTER_VALIDATE_EMAIL);
            },
        );

        $functions[] = new ExpressionFunction(
            'is_url',
            $compiler,
            static function (array $arguments, string $input): bool {
                return \filter_var($input, \FILTER_VALIDATE_URL);
            },
        );

        $functions[] = new ExpressionFunction(
            'is_integer',
            $compiler,
            static function (array $arguments, string $input): bool {
                return \strval($input) === \strval(\intval($input));
            },
        );

        return $functions;
    }
}
