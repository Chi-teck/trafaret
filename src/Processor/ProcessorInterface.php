<?php

namespace Trafaret\Processor;

use Trafaret\TrafaretInterface;

interface ProcessorInterface
{
    /**
     * Processes the trafaret before applying.
     */
    public function processTrafaret(TrafaretInterface $trafaret): TrafaretInterface;

    /**
     ** Processes the input before applying the trafaret.
     */
    public function processInput(string $input): string;
}
