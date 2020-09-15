<?php

declare(strict_types=1);

namespace Trafaret\Processor;

final class EmptyLine extends AbstractProcessor
{
    protected function doProcess(string $input): string
    {
        $input = \preg_replace('/^[ \t]*[\r\n]+/m', '', $input);
        return \preg_replace('/[\r\n]$/', '', $input);
    }
}
