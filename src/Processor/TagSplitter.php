<?php

declare(strict_types=1);

namespace Trafaret\Processor;

final class TagSplitter extends AbstractProcessor
{
    protected function doProcess(string $input): string
    {
        return \preg_replace('#>\s*<([a-z])#i', ">\n<\$1", $input);
    }
}
