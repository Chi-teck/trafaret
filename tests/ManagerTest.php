<?php

declare(strict_types=1);

namespace Trafaret\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Validation;
use Trafaret\Manager;
use Trafaret\Processor\Dummy;
use Trafaret\Processor\LeadingSpace;
use Trafaret\Trafaret;

final class ManagerTest extends TestCase
{
    private $manager;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new Manager(
            Validation::createValidator(),
            new Dummy(),
        );
    }

    /**
     * @dataProvider managerDataProvider()
     */
    public function testManager(Trafaret $trafaret, string $input, array $expected_data = [], ?string $exception_message = null): void
    {
        if ($exception_message) {
            self::expectExceptionMessage($exception_message);
        }
        $data = $this->manager->apply($trafaret, $input);
        self::assertSame($expected_data, $data);
    }
    
    public function managerDataProvider(): array
    {
        // -- Input without variables.
        $data[0] = [
            new Trafaret('foo'),
            'foo',
            [],
        ];

        // -- Input with a variable.
        $data[1] = [
            new Trafaret('-={{ foo }}=-'),
            '-=bar=-',
            ['foo' => 'bar'],
        ];

        // -- Multiline input.
        $data[2] = [
            new Trafaret(
                <<< 'TXT'
                aaa{{ v1 }} {{ v2 }}
                {{ v3 }}
                    ddd{{ v4 }}
                TXT,
            ),
            <<< 'TXT'
            aaa bbb
            ccc
                ddd eee 
            TXT,
            ['v1' => '', 'v2' => 'bbb', 'v3' => 'ccc', 'v4' => ' eee '],
        ];

        // -- Input with wrong line.
        $data[3] = [
            new Trafaret(
                <<< 'TXT'
                aaa
                bbb
                ccc
                TXT,
            ),
            <<< 'TXT'
                aaa
                zzz
                ccc
                TXT,
            [],
            'Expected line "bbb" does not match "zzz".',
        ];

        // -- Input with missing line.
        $data[4] = [
            new Trafaret(
                <<< 'TXT'
                aaa
                bbb
                ccc
                TXT,
            ),
            <<< 'TXT'
                aaa
                bbb
                TXT,
            [],
            'Expected line "ccc" does not match "".',
        ];

        // -- Input with missing line.
        $data[5] = [
            new Trafaret(
                <<< 'TXT'
                aaa
                bbb
                TXT,
            ),
            <<< 'TXT'
                aaa
                bbb
                ccc
                TXT,
            [],
            'Expected line "" does not match "ccc".',
        ];

        // -- Trafaret with a constraint.
        $data[6] = [
            new Trafaret(
                'aaa {{ bbb }} ccc',
                ['bbb' => new GreaterThan(10)],
            ),
            'aaa 15 ccc',
            ['bbb' => '15'],
        ];

        // -- Input with wrong placeholder.
        $data[7] = [
            new Trafaret(
                'aaa {{ bbb }} ccc',
                ['bbb' => new GreaterThan(10)],
            ),
            'aaa 5 ccc',
            [],
            "Unexpected value \"5\" for \"bbb\" variable.\nThis value should be greater than 10.",
        ];

        // -- Trafaret with ignored placeholder.
        $data[8] = [
            new Trafaret('aaa {{ -bbb }} ccc'),
            'aaa 15 ccc',
        ];

        // -- Trafaret with ignored placeholder and a constraint.
        $data[9] = [
            new Trafaret(
                'aaa {{ -bbb }} ccc',
                ['-bbb' => new GreaterThan(10)],
            ),
            'aaa 5 ccc',
            [],
            "Unexpected value \"5\" for \"-bbb\" variable.\nThis value should be greater than 10.",
        ];

        return $data;
    }

    public function testManagerWithProcessor(): void
    {
        $manager = new Manager(
            Validation::createValidator(),
            new LeadingSpace(),
        );

        $trafaret = new Trafaret("\t  xxx {{ foo }}");
        $input = "  \txxx bar";

        $data = $manager->apply($trafaret, $input);
        self::assertSame(['foo' => 'bar'], $data);
    }
}
