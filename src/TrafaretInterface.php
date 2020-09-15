<?php

declare(strict_types=1);

namespace Trafaret;

interface TrafaretInterface
{
    public function getTemplate(): string;

    public function getConstraints(): array;

    public function cloneWithTemplate(string $template): TrafaretInterface;
}