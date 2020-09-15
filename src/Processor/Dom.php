<?php

declare(strict_types=1);

namespace Trafaret\Processor;

use Trafaret\TrafaretInterface;

final class Dom implements ProcessorInterface
{
    public function processTrafaret(TrafaretInterface $trafaret): TrafaretInterface
    {
        return $trafaret->cloneWithTemplate(self::normalize($trafaret->getTemplate()));
    }

    public function processInput(string $input): string
    {
        return self::normalize($input);
    }

    /**
     * Normalizes an HTML snippet.
     */
    private static function normalize(string $html): string
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
