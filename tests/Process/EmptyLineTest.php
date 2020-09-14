<?php

namespace Trafaret\Processor\Tests;

use PHPUnit\Framework\TestCase;
use Trafaret\Processor\EmptyLine;
use Trafaret\Trafaret;

final class EmptyLineTest extends TestCase
{
    public function testProcessTrafaret(): void
    {
        $processor = new EmptyLine();

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
            $processor->processTrafaret(new Trafaret($template)),
        );
    }

    public function testProcessInput(): void
    {
        $processor = new EmptyLine();

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

        self::assertEquals($expected_processed_input, $processor->processInput($input));
    }
}
