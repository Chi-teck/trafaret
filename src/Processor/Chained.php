<?php

declare(strict_types=1);

namespace Trafaret\Processor;

final class Chained extends AbstractProcessor
{
    private $processors;

    public function __construct(ProcessorInterface ...$processors)
    {
        $this->processors = $processors;
    }

    protected function doProcess(string $input): string
    {
        foreach ($this->processors as $processor) {
            $input = $processor->processInput($input);
        }
        return $input;
    }
}
