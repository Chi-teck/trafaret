<?php

namespace Trafaret\Processor\Tests;

use PHPUnit\Framework\TestCase;
use Trafaret\Processor\Dom;
use Trafaret\Trafaret;

final class DomTest extends TestCase
{
    public function testProcessTrafaret(): void
    {
        $processor = new Dom();

        $expected_processed_template = <<< 'HTML'
            <div class="wrapper">
            <span>text</span>
            </div>

            HTML;

        $template = <<< 'HTML'

            <div   class   =   "wrapper"      >
            <span   >text</span  >
            </div>

            HTML;

        self::assertEquals(
            new Trafaret($expected_processed_template),
            $processor->processTrafaret(new Trafaret($template)),
        );
    }

    public function testProcessInput(): void
    {
        $processor = new Dom();

        $expected_processed_input = <<< 'HTML'
            <div class="wrapper">
            <span>text</span>
            </div>

            HTML;

        $input = <<< 'HTML'

            <div   class   =   "wrapper"      >
            <span   >text</span  >
            </div>

            HTML;

        self::assertEquals($expected_processed_input, $processor->processInput($input));
    }
}
