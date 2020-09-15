<?php

declare(strict_types=1);

namespace Trafaret\Processor;

use Trafaret\TrafaretInterface;

final class TagSplitter implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function processTrafaret(TrafaretInterface $trafaret): TrafaretInterface
    {
        $template = self::doProcess($trafaret->getTemplate());
        return $trafaret->cloneWithTemplate($template);
    }

    /**
     * {@inheritdoc}
     */
    public function processInput(string $input): string
    {
        return self::doProcess($input);
    }

    private static function doProcess(string $input): string
    {
        return \preg_replace('#>\s*<([a-z])#i', ">\n<\$1", $input);
    }
}
