<?php

declare(strict_types=1);

namespace Trafaret\Processor;

use Trafaret\TrafaretInterface;

final class LeadingSpace implements ProcessorInterface
{
    public function processTrafaret(TrafaretInterface $trafaret): TrafaretInterface
    {
        $template = \preg_replace('/^[ \t]+/m', '', $trafaret->getTemplate());
        return $trafaret->cloneWithTemplate($template);
    }

    public function processInput(string $input): string
    {
        return \preg_replace('/^[ \t]+/m', '', $input);
    }
}
