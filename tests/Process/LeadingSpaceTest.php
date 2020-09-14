<?php

namespace Trafaret\Processor\Tests;

use PHPUnit\Framework\TestCase;
use Trafaret\Processor\LeadingSpace;
use Trafaret\Trafaret;

final class LeadingSpaceTest extends TestCase
{
    public function testProcessTrafaret(): void
    {
        $processor = new LeadingSpace();

        $expected_processed_template = \implode(
            [
                "\n",
                "aaa  \t\n",
                "bbb\n",
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
        $processor = new LeadingSpace();

        $expected_processed_input = \implode(
            [
                "\n",
                "aaa  \t\n",
                "bbb\n",
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
