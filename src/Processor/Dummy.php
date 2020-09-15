<?php

declare(strict_types=1);

namespace Trafaret\Processor;

use Trafaret\TrafaretInterface;

final class Dummy implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function processTrafaret(TrafaretInterface $trafaret): TrafaretInterface
    {
        return $trafaret;
    }

    /**
     * {@inheritdoc}
     */
    public function processInput(string $input): string
    {
        return $input;
    }
}
