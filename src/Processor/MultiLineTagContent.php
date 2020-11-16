<?php

declare(strict_types=1);

namespace Trafaret\Processor;

final class MultiLineTagContent extends AbstractProcessor
{
    protected function doProcess(string $input): string
    {
        return \preg_replace_callback(
            '#>([^<]+?)</#is',
            [self::class, 'processChunk'],
            $input,
        );
    }

    // @phpcs:ignore SlevomatCodingStandard.Classes.UnusedPrivateElements.UnusedMethod
    private static function processChunk(array $matches): string
    {
        $output = \trim($matches[1]);
        if ($output) {
            $output = \preg_replace('#[\s]+#', ' ', $output);
            return ">$output</";
        }
        return $matches[0];
    }
}
