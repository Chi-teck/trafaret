<?php

namespace Trafaret;

final class Trafaret
{
    private $template;
    private $context;

    public function __construct(string $template, array $context = [])
    {
        $this->template = $template;
        $this->context = $context;
    }
    
    public static function createFromFile(string $file_name, array $context = []): self
    {
        if (!\is_file($file_name)) {
            throw new \InvalidArgumentException(\sprintf('Could not load file "%s".', $file_name));
        }
        $template = \file_get_contents($file_name);
        return new self($template, $context);
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getContext(): array
    {
        return $this->context;
    }
}
