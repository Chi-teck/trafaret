<?php

declare(strict_types=1);

namespace Trafaret\PhpUnit;

use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Comparator\ComparisonFailure;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Trafaret\Exception\UnexpectedLineException;
use Trafaret\Manager;
use Trafaret\Processor\Chained;
use Trafaret\Processor\EmptyLine;
use Trafaret\Processor\LeadingSpace;
use Trafaret\Processor\MultiLineTagContent;
use Trafaret\Processor\ProcessorInterface;
use Trafaret\Processor\TagSplitter;
use Trafaret\Trafaret;

trait TrafaretTrait
{
    private $trafaretManager;

    protected function getTrafaretValidator(): ValidatorInterface
    {
        return Validation::createValidator();
    }

    protected function getTrafaretProcessor(): ProcessorInterface
    {
        return new Chained(
            new LeadingSpace(),
            new TagSplitter(),
            new MultiLineTagContent(),
            new EmptyLine(),
        );
    }

    protected function getTrafaretManager(): Manager
    {
        if (!$this->trafaretManager) {
            $this->trafaretManager = new Manager(
                $this->getTrafaretValidator(),
                $this->getTrafaretProcessor(),
            );
        }
        return $this->trafaretManager;
    }

    /**
     * @param \Trafaret\TrafaretInterface|string $trafaret
     *   A trafaret object or trafaret template.
     */
    protected function assertStringByTrafaret($trafaret, string $actual): void
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
