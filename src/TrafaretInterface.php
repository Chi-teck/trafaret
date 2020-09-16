<?php

declare(strict_types=1);

namespace Trafaret;

interface TrafaretInterface
{
    /**
     * Returns trafaret template.
     */
    public function getTemplate(): string;

    /**
     * Returns trafaret constraints.
     *
     * @return \Symfony\Component\Validator\Constraint[]
     */
    public function getConstraints(): array;

    /**
     * Creates a new trafaret with a given template.
     */
    public function cloneWithTemplate(string $template): TrafaretInterface;

    /**
     * Returns a new trafaret with a given constraint list.
     *
     * @param \Symfony\Component\Validator\Constraint[] $constraints
     */
    public function cloneWithConstraints(array $constraints): TrafaretInterface;
}
