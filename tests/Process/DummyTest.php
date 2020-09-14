<?php

namespace Trafaret\Processor\Tests;

use PHPUnit\Framework\TestCase;
use Trafaret\Processor\Dummy;
use Trafaret\Trafaret;

final class DummyTest extends TestCase
{
    public function testProcessor(): void
    {
        $processor = new Dummy();

        $trafaret = new Trafaret(' foo ');
        self::assertEquals($trafaret, $processor->processTrafaret($trafaret));

        $input = ' bar ';
        self::assertSame($input, $processor->processInput($input));
    }
}
