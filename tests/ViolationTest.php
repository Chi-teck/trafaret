<?php

namespace Trafaret\Tests;

use PHPUnit\Framework\TestCase;
use Trafaret\Violation;

final class ViolationTest extends TestCase
{
    public function testViolation(): void
    {
        $violation = new Violation('Message');
        self::assertSame('Message', (string) $violation);
        self::assertSame('Message', $violation->getMessage());
    }
}
