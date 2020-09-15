<?php

declare(strict_types=1);

namespace Trafaret\Processor\Tests;

use PHPUnit\Framework\TestCase;
use Trafaret\Processor\TagSplitter;
use Trafaret\Trafaret;

final class TagSplitterTest extends TestCase
{
    /**
     * @dataProvider dataProvider()
     */
    public function testProcessTrafaret(string $expected_processed_template, string $template): void
    {
        $processor = new TagSplitter();

        self::assertEquals(
            new Trafaret($expected_processed_template),
            $processor->processTrafaret(new Trafaret($template)),
        );
    }

    /**
     * @dataProvider dataProvider()
     */
    public function testProcessInput(string $expected_processed_input, string $input): void
    {
        $processor = new TagSplitter();

        self::assertEquals(
            $expected_processed_input,
            $processor->processInput($input),
        );
    }

    public static function dataProvider(): array
    {
        $data[] = [
            <<< 'HTML'
            <i>aaa</i>
            <b>bbb</b>
            HTML,
            '<i>aaa</i><b>bbb</b>'
        ];

        $data[] = [
            <<< 'HTML'
              <i>aaa</i>
            <b>bbb</b>
            HTML,
            '  <i>aaa</i>  <b>bbb</b>'
        ];

        $data[] = [
            <<< 'HTML'
            <test/>
            <em>aaa</em>
            HTML,
            '<test/><em>aaa</em>'
        ];

        $data[] = [
            <<< 'HTML'
            <test></test>
            HTML,
            '<test></test>'
        ];

        $data[] = [
            <<< 'HTML'
            <test>< test>
            HTML,
            '<test>< test>'
        ];

        return $data;
    }
}
