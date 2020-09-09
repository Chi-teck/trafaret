<?php

namespace Trafaret\PhpUnit;

use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Comparator\ComparisonFailure;
use Trafaret\ComparableViolation;
use Trafaret\Trafaret;
use Trafaret\Validator;

trait TrafaretTrait
{
    private $trafaretValidator;

    private function getTrafaretValidator(): Validator
    {
        if (!$this->trafaretValidator) {
            $this->trafaretValidator = Validator::createDefault();
        }
        return $this->trafaretValidator;
    }

    /**
     * @param \Trafaret\Trafaret|string $trafaret
     *   A trafaret object or trafaret template.
     */
    public function assertStringByTrafaret($trafaret, string $actual): void
    {
        if (\is_string($trafaret)) {
            $trafaret = new Trafaret($trafaret);
        }

        $validator = $this->getTrafaretValidator();

        $violations = $validator->validate($actual, $trafaret);
        if ($violation = $violations->getFirst()) {
            if ($violation instanceof ComparableViolation) {
                $expected = $violation->getExpected();
                $actual = $violation->getGetActual();
                $failure = new ComparisonFailure($expected, $actual, $expected, $actual);
                throw new ExpectationFailedException('Failed asserting that two lines are equal.', $failure);
            }
            else {
                $this->fail($violation);
            }
        }
        self::assertTrue(true);
    }
}
