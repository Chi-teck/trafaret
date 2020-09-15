<?php

declare(strict_types=1);

namespace Trafaret\Processor;

use Trafaret\TrafaretInterface;

abstract class AbstractProcessor implements ProcessorInterface
{
    public function processTrafaret(TrafaretInterface $trafaret): TrafaretInterface
    {
        return $trafaret->cloneWithTemplate(
            $this->doProcess($trafaret->getTemplate()),
        );
    }

    public function processInput(string $input): string
    {
        return $this->doProcess($input);
    }

    protected function doProcess(string $input): string
    {
        return $input;
    }
}
