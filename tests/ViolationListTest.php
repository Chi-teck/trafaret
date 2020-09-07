<?php

namespace Trafaret\Tests;

use PHPUnit\Framework\TestCase;
use Trafaret\Violation;
use Trafaret\ViolationList;

final class ViolationListTest extends TestCase
{
    public function testArrayAccess(): void
    {
        $list = self::getViolationList();

        self::assertInstanceOf(\ArrayAccess::class, $list);

        // -- ::offsetExists().
        self::assertTrue(isset($list[0]));
        self::assertTrue(isset($list[1]));
        self::assertTrue(isset($list[2]));
        self::assertFalse(isset($list[3]));

        // -- ::offsetSet().
        $list[] = new Violation('Message 3');
        self::assertTrue(isset($list[3]));

        // -- ::offsetGet().
        self::assertSame('Message 0', $list[0]->getMessage());
        self::assertSame('Message 1', $list[1]->getMessage());
        self::assertSame('Message 2', $list[2]->getMessage());
        self::assertSame('Message 3', $list[3]->getMessage());

        // -- ::offsetUnset().
        unset($list[3]);
        self::assertFalse(isset($list[3]));
    }

    public function testCountable(): void
    {
        $list = self::getViolationList();
        self::assertInstanceOf(\Countable::class, $list);

        self::assertSame(3, \count($list));

        unset($list[2]);
        self::assertSame(2, \count($list));
    }

    public function testTraversable(): void
    {
        $list = self::getViolationList();
        self::assertInstanceOf(\Traversable::class, $list);

        $messages = [];
        foreach ($list as $violation) {
            $messages[] = (string) $violation;
        }

        $expected_messages = [
            'Message 0',
            'Message 1',
            'Message 2',
        ];
        self::assertSame($expected_messages, $messages);
    }

    public function testFirst(): void
    {
        self::assertNull((new ViolationList())->getFirst());
        self::assertSame(self::getViolationList()->getFirst()->getMessage(), 'Message 0');
    }

    public function testLast(): void
    {
        self::assertNull((new ViolationList())->getLast());
        self::assertSame(self::getViolationList()->getLast()->getMessage(), 'Message 2');
    }

    public function testToString(): void
    {
        $expected_output = <<< 'TEXT'
        [ERROR] Message 0
        [ERROR] Message 1
        [ERROR] Message 2
        
        TEXT;
        self::assertSame($expected_output, (string) self::getViolationList());
    }

    private static function getViolationList(): ViolationList
    {
        $violations = new ViolationList();
        $violations[] = new Violation('Message 0');
        $violations[] = new Violation('Message 1');
        $violations[] = new Violation('Message 2');
        return $violations;
    }
}
