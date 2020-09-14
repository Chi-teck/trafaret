<?php

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
     *
     * @todo Handle DOM errors.
     */
    private static function normalize(string $html): string
    {
        $dom = new \DOMDocument();
        $dom->loadHTML($html, \LIBXML_HTML_NOIMPLIED | \LIBXML_HTML_NODEFDTD);
        $dom->normalize();
        return $dom->saveHTML();
    }
}
