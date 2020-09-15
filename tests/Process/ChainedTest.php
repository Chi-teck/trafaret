<?php

declare(strict_types=1);

namespace Trafaret\Processor\Tests;

use PHPUnit\Framework\TestCase;
use Trafaret\Processor\Chained;
use Trafaret\Processor\ProcessorInterface;
use Trafaret\Trafaret;
use Trafaret\TrafaretInterface;

final class ChainedTest extends TestCase
{
    private $processor;

    public function setUp(): void
    {
        parent::setUp();

        $processor_1 = new class implements ProcessorInterface {
            public function processTrafaret(TrafaretInterface $trafaret): TrafaretInterface
            {
                $template = \str_replace(
                    ['{alpha}', '{common}'],
                    ['{alpha-1}', '{common-1}'],
                    $trafaret->getTemplate(),
                );
                return new Trafaret($template);
            }
            public function processInput(string $input): string
            {
                return \str_replace(
                    ['{alpha}', '{common}'],
                    ['{alpha-1}', '{common-1}'],
                    $input,
                );
            }
        };

        $processor_2 = new class implements ProcessorInterface {
            public function processTrafaret(TrafaretInterface $trafaret): TrafaretInterface
            {
                $template = \str_replace(
                    ['{beta}', '{common}'],
                    ['{beta-2}', '{common-2}'],
                    $trafaret->getTemplate(),
                );
                return new Trafaret($template);
            }
            public function processInput(string $input): string
            {
                return \str_replace(
                    ['{beta}', '{common}'],
                    ['{beta-2}', '{common-2}'],
                    $input,
                );
            }
        };

        $processor_3 = new class implements ProcessorInterface {
            public function processTrafaret(TrafaretInterface $trafaret): TrafaretInterface
            {
                $template = \str_replace(
                    ['{gamma}', '{common}'],
                    ['{gamma-3}', '{common-3}'],
                    $trafaret->getTemplate(),
                );
                return new Trafaret($template);
            }
            public function processInput(string $input): string
            {
                return \str_replace(
                    ['{gamma}', '{common}'],
                    ['{gamma-3}', '{common-3}'],
                    $input,
                );
            }
        };

        $this->processor = new Chained($processor_1, $processor_2, $processor_3);
    }

    public function testProcessTrafaret(): void
    {
        // Make sure the {common} token is processed by processor_1.
        $expected_processed_template = '{common-1}{alpha-1}{beta-2}{gamma-3}';
        $template = '{common}{alpha}{beta}{gamma}';

        self::assertEquals(
            new Trafaret($expected_processed_template),
            $this->processor->processTrafaret(new Trafaret($template)),
        );
    }

    public function testProcessInput(): void
    {
        // Make sure the {common} token is processed by processor_1.
        $expected_processed_input = '{common-1}{alpha-1}{beta-2}{gamma-3}';
        $input = '{common}{alpha}{beta}{gamma}';

        self::assertEquals(
            $expected_processed_input,
            $this->processor->processInput($input),
        );
    }
}
