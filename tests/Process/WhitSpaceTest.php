<?php

namespace Trafaret\Processor\Tests;

use PHPUnit\Framework\TestCase;
use Trafaret\Processor\Chained;
use Trafaret\Processor\EmptyLine;
use Trafaret\Processor\LeadingSpace;
use Trafaret\Processor\TrailingSpace;
use Trafaret\Trafaret;

final class WhitSpaceTest extends TestCase
{
    private $processor;

    public function setUp(): void
    {
        parent::setUp();
        $this->processor = new Chained(new LeadingSpace(), new TrailingSpace(), new EmptyLine());
    }

    public function testProcessTrafaret(): void
    {
        $expected_processed_template = <<< 'TXT'
            aaa
            bbb
            ccc
            TXT;

        $template = <<< 'TXT'

            aaa  
              bbb

            ccc

            TXT;

        self::assertEquals(
            new Trafaret($expected_processed_template),
            $this->processor->processTrafaret(new Trafaret($template)),
        );
    }

    public function testProcessInput(): void
    {
        $expected_processed_input = <<< 'TXT'
            aaa
            bbb
            ccc
            TXT;

        $input = <<< 'TXT'

            aaa  
              bbb

            ccc

            TXT;

        self::assertEquals($expected_processed_input, $this->processor->processInput($input));
    }
}
