<?php

declare(strict_types=1);

namespace Trafaret\Processor;

final class Dom extends AbstractProcessor
{
    protected function doProcess(string $html): string
    {
        $dom = new \DOMDocument();
        // Ignore warnings during HTML soup loading.
        @$dom->loadHTML($html, \LIBXML_HTML_NOIMPLIED | \LIBXML_HTML_NODEFDTD);
        $dom->normalize();
        $result = $dom->saveHTML();
        if ($result === false) {
            throw new \RuntimeException('Could not save HTML');
        }
        return $result;
    }
}
