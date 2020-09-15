<?php

declare(strict_types=1);

namespace Trafaret\Processor;

final class TrailingSpace extends AbstractProcessor
{
    protected function doProcess(string $input): string
    {
        return \preg_replace('/[ \t]+$/m', '', $input);
    }
}
