<?php

namespace Trafaret;

final class ViolationList implements \ArrayAccess, \IteratorAggregate, \Countable
{
    private $violations = [];

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset): bool
    {
        return isset($this->violations[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset): Violation
    {
        return $this->violations[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $violation): void
    {
        if (!$violation instanceof Violation) {
            throw new \InvalidArgumentException(
                \sprintf('A violation must be an instance of %s class.', Violation::class),
            );
        }

        if (\is_null($offset)) {
            $this->violations[] = $violation;
        } else {
            $this->violations[$offset] = $violation;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset): void
    {
        unset($this->violations[$offset]);
    }

    /**
     * Returns first violation.
     */
    public function getFirst(): ?Violation
    {
        return $this->violations[0] ?? null;
    }

    /**
     * Returns last violation.
     */
    public function getLast(): ?Violation
    {
        $last_key = \array_key_last($this->violations);
        return $last_key ? $this->violations[$last_key] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->violations);
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return \count($this->violations);
    }

    /**
     * Returns a string representation of the list.
     */
    public function __toString(): string
    {
        $output = '';
        foreach ($this->violations as $violation) {
            $output .= \sprintf("[ERROR] %s\n", $violation);
        }
        return $output;
    }
}
