<?php

namespace Trafaret\Processor;

use Trafaret\TrafaretInterface;

final class Chained implements ProcessorInterface
{
    private $processors;

    public function __construct(ProcessorInterface ...$processors)
    {
        $this->processors = $processors;
    }

    public function processTrafaret(TrafaretInterface $trafaret): TrafaretInterface
    {
        foreach ($this->processors as $processor) {
            $trafaret = $processor->processTrafaret($trafaret);
        }
        return $trafaret;
    }

    public function processInput(string $input): string
    {
        foreach ($this->processors as $processor) {
            $input = $processor->processInput($input);
        }
        return $input;
    }
}
