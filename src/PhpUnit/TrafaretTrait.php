<?php

declare(strict_types=1);

namespace Trafaret\PhpUnit;

use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Comparator\ComparisonFailure;
use Symfony\Component\Validator\Validation;
use Trafaret\Exception\UnexpectedLineException;
use Trafaret\Manager;
use Trafaret\Processor\Chained;
use Trafaret\Processor\EmptyLine;
use Trafaret\Processor\LeadingSpace;
use Trafaret\Trafaret;

trait TrafaretTrait
{
    private $trafaretManager;

    private function getTrafaretManager(): Manager
    {
        if (!$this->trafaretManager) {
            $this->trafaretManager = new Manager(
                Validation::createValidator(),
                new Chained(
                    new LeadingSpace(),
                    new EmptyLine(),
                ),
            );
        }
        return $this->trafaretManager;
    }

    /**
     * @param \Trafaret\TrafaretInterface|string $trafaret
     *   A trafaret object or trafaret template.
     */
    public function assertStringByTrafaret($trafaret, string $actual): void
    {
        if (\is_string($trafaret)) {
            $trafaret = new Trafaret($trafaret);
        }

        $manager = $this->getTrafaretManager();

        try {
            $manager->apply($trafaret, $actual);
        }
        catch (UnexpectedLineException $exception) {
            $expected = $exception->getExpectedLine();
            $actual = $exception->getGetActualLine();
            $failure = new ComparisonFailure($expected, $actual, $expected, $actual);
            throw new ExpectationFailedException('Failed asserting that two lines are equal.', $failure);
        }

        self::assertTrue(true);
    }
}
