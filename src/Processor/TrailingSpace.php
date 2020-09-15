<?php

declare(strict_types=1);

namespace Trafaret\Processor;

use Trafaret\TrafaretInterface;

final class TrailingSpace implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function processTrafaret(TrafaretInterface $trafaret): TrafaretInterface
    {
        $template = \preg_replace('/[ \t]+$/m', '', $trafaret->getTemplate());
        return $trafaret->cloneWithTemplate($template);
    }

    /**
     * {@inheritdoc}
     */
    public function processInput(string $input): string
    {
        return \preg_replace('/[ \t]+$/m', '', $input);
    }
}
