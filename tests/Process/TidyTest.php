<?php

declare(strict_types=1);

namespace Trafaret\Processor\Tests;

use PHPUnit\Framework\TestCase;
use Trafaret\Processor\Tidy;
use Trafaret\Trafaret;

final class TidyTest extends TestCase
{
    public function testProcess(): void
    {
        $processor = new Tidy();

        $expected_html = <<< 'HTML'
            <div class="example">
                <span class="vvv" id="vvv">fff</span>
                <p>
                    Lorem ipsum dolor sit amet
                </p>
            </div>
            HTML;

        $html = <<< 'HTML'
            <div class="example">
            <span         class="vvv"
                          id="vvv"
                          >fff</span>
            <p> Lorem ipsum dolor sit
            amet
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

    public function testProcessWithCustomConfig(): void
    {
        $processor = new Tidy(['indent-spaces' => 2]);

        $expected_html = <<< 'HTML'
            <div class="example">
              <span class="vvv" id="vvv">fff</span>
              <p>
                Lorem ipsum dolor sit amet
              </p>
            </div>
            HTML;

        $html = <<< 'HTML'
            <div class="example">
            <span         class="vvv"
                          id="vvv"
                          >fff</span>
            <p> Lorem ipsum dolor sit
            amet
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
