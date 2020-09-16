<?php

declare(strict_types=1);

namespace Trafaret;

final class Trafaret implements TrafaretInterface
{
    private $template;
    private $constraints;

    public function __construct(string $template, array $constraints = [])
    {
        $this->template = $template;
        $this->constraints = $constraints;
    }
    
    public static function createFromFile(string $file_name, array $constraints = []): self
    {
        if (!\is_file($file_name)) {
            throw new \InvalidArgumentException(\sprintf('Could not load file "%s".', $file_name));
        }
        $template = \file_get_contents($file_name);
        return new self($template, $constraints);
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * {@inheritdoc}
     */
    public function getConstraints(): array
    {
        return $this->constraints;
    }

    /**
     * {@inheritdoc}
     */
    public function cloneWithTemplate(string $template): TrafaretInterface
    {
        return new self($template, $this->getConstraints());
    }

    /**
     * {@inheritdoc}
     */
    public function cloneWithConstraints(array $constraints): TrafaretInterface
    {
        return new self($this->getTemplate(), $constraints);
    }
}
