<?php

declare(strict_types=1);

namespace Trafaret\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Negative;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Trafaret\Trafaret;

final class TrafaretTest extends TestCase
{
    private $trafaret;

    protected function setUp(): void
    {
        parent::setUp();
        $this->trafaret = new Trafaret('TEMPLATE', [new Positive()]);
    }

    public function testGetters(): void
    {
        self::assertSame('TEMPLATE', $this->trafaret->getTemplate());
        self::assertEquals([new Positive()], $this->trafaret->getConstraints());
    }

    public function testCloneWithTemplate(): void
    {
        $expected_trafaret = new Trafaret('NEW TEMPLATE', [new Positive()]);
        self::assertEquals($expected_trafaret, $this->trafaret->cloneWithTemplate('NEW TEMPLATE'));
    }

    public function testCloneWithConstraints(): void
    {
        $expected_trafaret = new Trafaret('TEMPLATE', [new Negative()]);
        self::assertEquals($expected_trafaret, $this->trafaret->cloneWithConstraints([new Negative()]));
    }

    public function testCreateFromFile(): void
    {
        $expected_trafaret = new Trafaret("Hello world!\n", [new NotBlank()]);
        $actual_trafaret = Trafaret::createFromFile(__DIR__ . '/example.trf', [new NotBlank()]);
        self::assertEquals($expected_trafaret, $actual_trafaret);

        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Could not load file');
        Trafaret::createFromFile(__DIR__ . '/not-exists.trf', [new NotBlank()]);
    }
}
