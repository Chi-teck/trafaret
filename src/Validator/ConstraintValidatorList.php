<?php

namespace Trafaret\Validator;

final class ConstraintValidatorList implements \ArrayAccess, \IteratorAggregate, \Countable
{
    private $validators = [];

    public function __construct(ConstraintValidatorInterface ...$validators)
    {
        foreach ($validators as $validator) {
            $this[] = $validator;
        }
    }

    public static function createDefault(): self
    {
        return new self(
            new Any(),
            new RegExp(),
            new GreaterThan(),
            new LessThan(),
            new Url(),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset): bool
    {
        return isset($this->validators[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset): ConstraintValidatorInterface
    {
        return $this->validators[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $validator): void
    {
        if (!$validator instanceof ConstraintValidatorInterface) {
            $message = \sprintf('Validators must implement %s interface.', ConstraintValidatorInterface::class);
            throw new \InvalidArgumentException($message);
        }

        if (\is_null($offset)) {
            $this->validators[] = $validator;
        }
        else {
            $this->validators[$offset] = $validator;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset): void
    {
        unset($this->validators[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->validators);
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return \count($this->validators);
    }
}
