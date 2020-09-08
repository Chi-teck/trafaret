<?php

namespace Trafaret;

use Trafaret\Exception\UnexpectedValueException;
use Trafaret\Exception\UnsupportedConstraintException;
use Trafaret\Exception\ValidatorNotFoundException;
use Trafaret\Constraint\ConstraintList;

final class Validator
{
    private const PLACEHOLDER = 'TRAFARET_PLACEHOLDER';
    private const EXPRESSION_PATTERN = '/{%\s*(.+?)\s*%}/';

    private $config;
    private $validators;

    public function __construct(Config $config, ConstraintList $validators)
    {
        $this->config = $config;
        $this->validators = $validators;
    }

    public function validate(string $input, string $trafaret): ViolationList
    {
        $input_lines = $this->split($input);
        $trafaret_lines = $this->split($trafaret);

        $violations = new ViolationList();

        $total_lines = \max(\count($trafaret_lines), \count($input_lines));
        for ($ln = 0; $ln < $total_lines; $ln++) {
            $constraints = [];

            $replace = static function (array $matches) use (&$constraints): string {
                $constraints[] = $matches[1];
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
                }
                elseif ($trafaret_line === null) {
                    $message = \sprintf('Line "%s" was not expected.', $input_line);
                }
                else {
                    $message = \sprintf('Expected line "%s" does not match "%s".', $trafaret_line, $input_line);
                }
                $violations[] = new Violation($message);
                continue;
            }
            \array_shift($matches);

            foreach ($constraints as $index => $constraint) {
                $value = $matches[$index][0];
                if ($violation = $this->validatePlaceholder($constraint, $value)) {
                    $violations[] = $violation;
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
            $lines = \array_values(\array_filter($lines));
        }
        return $lines;
    }

    private function validatePlaceholder(string $constraint, string $value): ?Violation
    {
        foreach ($this->validators as $validator) {
            try {
                $validator->validate($constraint, $value);
            }
            catch (UnsupportedConstraintException $exception) {
                continue;
            }
            catch (UnexpectedValueException $exception) {
                return new Violation($exception->getMessage());
            }
            return null;
        }

        throw new ValidatorNotFoundException(
            \sprintf('Could not find validator matching "%s" constraint.', $constraint),
        );
    }
}
