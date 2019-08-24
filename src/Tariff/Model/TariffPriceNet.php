<?php

namespace App\Tariff\Model;

use App\Common\Result\Ok;
use App\Common\Result\Result;
use App\Promocode\Model\Discount\Discount;
use App\Tariff\Model\Error\TariffSegmentNotFound;
use App\Tariff\Model\Exception\TariffSegmentsCantIntersects;
use DateTimeImmutable;
use Money\Money;

// TODO цены должны не только не пересекаться, но ещё и идти всегда подряд?
final class TariffPriceNet
{
    /**
     * @var TariffSegment[]
     */
    private $segments;

    public function __construct(array $segments)
    {
        static::assertSegmentsNotEmpty($segments);
        static::assertPricesNotIntersects($segments);
        $this->segments = $segments;
    }

    public function calculateSum(Discount $discount, DateTimeImmutable $asOf): Result
    {
        $result = $this->findSegmentAsOF($asOf);
        if ($result->isErr()) {
            return $result;
        }
        /** @var TariffSegment $segment */
        $segment = $result->value();

        return new Ok($segment->calculateSum($discount));
    }

    private function findSegmentAsOF(DateTimeImmutable $asOf): Result
    {
        foreach ($this->segments as $segment) {
            if ($segment->includes($asOf)) {
                return new Ok($segment);
            }
        }

        return new TariffSegmentNotFound();
    }

    /** @var TariffSegment[] $segments */
    private static function assertPricesNotIntersects(array $segments): void
    {
        foreach ($segments as $outerIndex => $outerSegment) {
            foreach ($segments as $innerIndex => $innerSegment) {
                if ($innerIndex <= $outerIndex) {
                    continue;
                }

                if ($outerSegment->intersects($innerSegment)) {
                    throw new TariffSegmentsCantIntersects();
                }
            }
        }
    }

    private static function assertSegmentsNotEmpty(array $segments): void
    {
        if ([] === $segments) {
            throw new \Exception('segments should not be empty');
        }
    }
}
