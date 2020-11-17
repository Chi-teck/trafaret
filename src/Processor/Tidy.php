<?php

declare(strict_types=1);

namespace Trafaret\Processor;

final class Tidy extends AbstractProcessor
{
    // @phpcs:ignore SlevomatCodingStandard.Classes.UnusedPrivateElements.WriteOnlyProperty
    private $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /** @noinspection PhpComposerExtensionStubsInspection */
    protected function doProcess(string $html): string
    {
        if (!\class_exists(\tidy::class)) {
            throw new \RuntimeException('Tidy extension is not installed.');
        }

        $config = $this->config += [
            'indent' => true,
            'indent-spaces' => 4,
            'doctype' => 'auto',
            'wrap' => 0,
            'show-body-only' => !\str_contains($html, '<body'),
        ];

        $tidy = new \tidy();
        $tidy->parseString($html, $config, 'utf8');
        $tidy->cleanRepair();

        return (string) $tidy;
    }
}
