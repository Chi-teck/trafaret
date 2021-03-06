<?php

declare(strict_types=1);

namespace Trafaret;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Trafaret\Exception\UnexpectedLineException;
use Trafaret\Exception\UnexpectedValueException;
use Trafaret\Processor\Chained;
use Trafaret\Processor\EmptyLine;
use Trafaret\Processor\LeadingSpace;
use Trafaret\Processor\ProcessorInterface;

final class Manager
{
    private const TMP_PLACEHOLDER = 'TRAFARET_PLACEHOLDER';
    private const PLACEHOLDER_PATTERN = '/{{\s*(.+?)\s*}}/';

    private $validator;
    private $processor;

    public function __construct(ValidatorInterface $validator, ProcessorInterface $processor)
    {
        $this->validator = $validator;
        $this->processor = $processor;
    }

    public static function createDefault(): self
    {
        return new self(
            Validation::createValidator(),
            new Chained(
                new LeadingSpace(),
                new EmptyLine(),
            ),
        );
    }

    /**
     * Returns data defined by trafaret placeholder.
     */
    public function apply(TrafaretInterface $trafaret, string $input): array
    {
        $trafaret = $this->processor->processTrafaret($trafaret);
        $input = $this->processor->processInput($input);

        $data = [];

        $template = \rtrim($trafaret->getTemplate());
        $constraints = $trafaret->getConstraints();

        $trafaret_lines = $this->split($template);
        $input_lines = $this->split($input);

        $total_lines = \max(\count($trafaret_lines), \count($input_lines));
        for ($ln = 0; $ln < $total_lines; $ln++) {
            $placeholders = [];

            $replace = static function (array $matches) use (&$placeholders): string {
                $placeholders[] = $matches[1];
                return self::TMP_PLACEHOLDER;
            };

            $input_line = $input_lines[$ln] ?? '';
            $trafaret_line = $trafaret_lines[$ln] ?? '';

            // -- Replace placeholder with a temporary value.
            $tmp_trafaret_line = \preg_replace_callback(self::PLACEHOLDER_PATTERN, $replace, $trafaret_line);

            // -- Turn the line into a regexp pattern.
            $pattern = '/^' . \str_replace(self::TMP_PLACEHOLDER, '(.*)', \preg_quote($tmp_trafaret_line, '/')) . '$/';

            // -- Search values.
            $values = [];
            if (!\preg_match_all($pattern, $input_line, $values)) {
                throw new UnexpectedLineException($trafaret_line, $input_line);
            }
            \array_shift($values);

            // -- Match named placeholders and found values.
            foreach ($placeholders as $index => $name) {
                $value = $values[$index][0];
                if (\array_key_exists($name, $constraints)) {
                    $constraint = $constraints[$name];
                    $violations = $this->validator->validate($value, $constraint);
                    if (\count($violations) > 0) {
                        throw new UnexpectedValueException($trafaret_line, $input_line, $violations[0], $name);
                    }
                }
                // Placeholders prefixed with '-' are ignored.
                if (!\str_starts_with($name, '-')) {
                    if (\str_ends_with($name, '[]')) {
                        $data[\substr($name, 0, -2)][] = $value;
                    }
                    else {
                        $data[$name] = $value;
                    }
                }
            }
        }

        return $data;
    }

    private function split(string $input): array
    {
        return \preg_split('/$\R?^/m', $input);
    }
}
