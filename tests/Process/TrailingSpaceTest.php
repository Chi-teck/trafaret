<?php

declare(strict_types=1);

namespace Trafaret\Processor\Tests;

use PHPUnit\Framework\TestCase;
use Trafaret\Processor\TrailingSpace;
use Trafaret\Trafaret;

final class TrailingSpaceTest extends TestCase
{
    public function testProcessTrafaret(): void
    {
        $processor = new TrailingSpace();

        $expected_processed_template = \implode(
            [
                "\n",
                "aaa\n",
                "\t  bbb\n",
            ],
        );

        $template = \implode(
            [
                "\n",
                "aaa  \t\n",
                "\t  bbb\n",
            ],
        );

        self::assertEquals(
            new Trafaret($expected_processed_template),
            $processor->processTrafaret(new Trafaret($template)),
        );
    }

    public function testProcessInput(): void
    {
        $processor = new TrailingSpace();

        $expected_processed_input = \implode(
            [
                "\n",
                "aaa\n",
                "\t  bbb\n",
            ],
        );

        $input = \implode(
            [
                "\n",
                "aaa  \t\n",
                "\t  bbb\n",
            ],
        );

        self::assertEquals($expected_processed_input, $processor->processInput($input));
    }
}
