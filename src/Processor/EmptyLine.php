<?php

namespace Trafaret\Processor;

use Trafaret\TrafaretInterface;

final class EmptyLine implements ProcessorInterface
{
    public function processTrafaret(TrafaretInterface $trafaret): TrafaretInterface
    {
        $template = \preg_replace('/^[ \t]*[\r\n]+/m', '', $trafaret->getTemplate());
        $template = \preg_replace('/[\r\n]$/', '', $template);
        return $trafaret->cloneWithTemplate($template);
    }

    public function processInput(string $input): string
    {
        $input = \preg_replace('/^[ \t]*[\r\n]+/m', '', $input);
        return \preg_replace('/[\r\n]$/', '', $input);
    }
}
