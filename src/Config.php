<?php

namespace Trafaret;

use InvalidArgumentException;

/**
 * A configuration for Trafaret validator.
 */
final class Config
{
    private $ignoreLeadingSpaces;
    private $ignoreTrailingSpaces;
    private $ignoreEmptyLines;

    public function __construct(array $data)
    {
        self::validateData($data);
        $this->ignoreLeadingSpaces = $data['ignore_leading_spaces'];
        $this->ignoreTrailingSpaces = $data['ignore_trailing_spaces'];
        $this->ignoreEmptyLines = $data['ignore_empty_lines'];
    }

    public static function createDefault(): self
    {
        return new self([
            'ignore_leading_spaces' => true,
            'ignore_trailing_spaces' => false,
            'ignore_empty_lines' => true,
        ]);
    }

    public function isLeadingSpacesIgnored(): bool
    {
        return $this->ignoreLeadingSpaces;
    }

    public function isTrailingSpacesIgnored(): bool
    {
        return $this->ignoreTrailingSpaces;
    }

    public function isEmptyLinesIgnored(): bool
    {
        return $this->ignoreEmptyLines;
    }

    private static function validateData(array $data): void
    {
        $keys = [
            'ignore_leading_spaces',
            'ignore_trailing_spaces',
            'ignore_empty_lines'
        ];
        foreach ($keys as $key) {
            if (!isset($data[$key])) {
                $message = \sprintf('Missing option "%s".', $key);
                throw new InvalidArgumentException($message);
            }
            elseif (!\is_bool($data[$key])) {
                $message = \sprintf('Option "%s" must be boolean, not "%s".', $key, \gettype($data[$key]));
                throw new InvalidArgumentException($message);
            }
        }
    }
}
