<?php

declare(strict_types=1);

namespace Trafaret\Processor\Tests;

use PHPUnit\Framework\TestCase;
use Trafaret\Processor\MultiLineTagContent;
use Trafaret\Trafaret;

final class MultiLineTagContentTest extends TestCase
{
    public function testProcess(): void
    {
        $processor = new MultiLineTagContent();

        $expected_html = <<< 'HTML'
            <div class="example">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor</p>
            </div>
            HTML;

        $html = <<< 'HTML'
            <div class="example">
                <p> Lorem
                ipsum dolor sit amet, 
                consectetur adipiscing elit,
                sed do eiusmod tempor
                </p>
            </div>
            HTML;

        self::assertEquals(
            $expected_html,
            $processor->processInput($html),
        );

        self::assertEquals(
            new Trafaret($expected_html),
            $processor->processTrafaret(new Trafaret($html)),
        );
    }
}
