<?php

namespace Trafaret;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

final class Validator
{
    private const PLACEHOLDER = 'TRAFARET_PLACEHOLDER';
    private const EXPRESSION_PATTERN = '/{%\s*(.+?)\s*%}/';

    private $expressionLanguage;
    private $config;

    public function __construct(ExpressionLanguage $expression_language, Config $config)
    {
        $this->expressionLanguage = $expression_language;
        $this->config = $config;
    }

    public static function createDefault(): self
    {
        return new self(
            new ExpressionLanguage(null, [new ExpressionFunctionProvider()]),
            Config::createDefault(),
        );
    }

    public function validate(string $input, Trafaret $trafaret): ViolationList
    {
        $template = $trafaret->getTemplate();
        $context = $trafaret->getContext();

        $input_lines = $this->split($input);
        $trafaret_lines = $this->split($template);

        $violations = new ViolationList();

        $total_lines = \max(\count($trafaret_lines), \count($input_lines));
        for ($ln = 0; $ln < $total_lines; $ln++) {
            $expressions = [];

            $replace = static function (array $matches) use (&$expressions): string {
                $expressions[] = $matches[1];
                return self::PLACEHOLDER;
            };

            $input_line = $input_lines[$ln] ?? null;
            $trafaret_line = $trafaret_lines[$ln] ?? null;

            $tmp_trafaret_line = \preg_replace_callback(self::EXPRESSION_PATTERN, $replace, $trafaret_line);
            $pattern = '/^' . \str_replace(self::PLACEHOLDER, '(.*)', \preg_quote($tmp_trafaret_line, '/')) . '$/';

            $matches = [];
            if (!\preg_match_all($pattern, $input_line, $matches)) {
                if ($input_line === null) {
                    $message = \sprintf('Line "%s" was not found.', $trafaret_line);
                } elseif ($trafaret_line === null) {
                    $message = \sprintf('Line "%s" was not expected.', $input_line);
                } else {
                    $message = \sprintf('Expected line "%s" does not match "%s".', $trafaret_line, $input_line);
                }
                $violations[] = new Violation($message);
                continue;
            }
            \array_shift($matches);


            foreach ($expressions as $index => $expression) {
                $value = $matches[$index][0];
                $values = ['value' => $value] + $context;
                if (!$this->expressionLanguage->evaluate($expression, $values)) {
                    $message = \sprintf('The value «%s» does not satisfy the «%s» expression.', $value, $expression);
                    $violations[] = new Violation($message);
                }
            }
        }
        return $violations;
    }

    private function split(string $input): array
    {
        $lines = \preg_split('/$\R?^/m', $input);
        if ($this->config->isLeadingSpacesIgnored()) {
            $lines = \array_map('ltrim', $lines);
        }
        if ($this->config->isTrailingSpacesIgnored()) {
            $lines = \array_map('rtrim', $lines);
        }
        if ($this->config->isEmptyLinesIgnored()) {
            $filter = static function (string $input): bool {
                return \strlen($input) > 0 && !\ctype_space($input);
            };
            $lines = \array_values(\array_filter($lines, $filter));
        }
        return $lines;
    }
}
