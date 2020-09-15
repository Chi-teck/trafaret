<?php

declare(strict_types=1);

namespace Trafaret\Processor;

use Trafaret\TrafaretInterface;

final class Dummy implements ProcessorInterface
{
    public function processTrafaret(TrafaretInterface $trafaret): TrafaretInterface
    {
        return $trafaret;
    }

    public function processInput(string $input): string
    {
        return $input;
    }
}
