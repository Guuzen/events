<?php



namespace App\Tariff\Model;

use App\Promocode\Model\Discount\Discount;
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

    public function calculateSum(Discount $discount, DateTimeImmutable $asOf): ?Money
    {
        $segment = $this->findSegmentAsOF($asOf);
        if (null === $segment) {
            return null;
        }

        return $segment->calculateSum($discount);
    }

    private function findSegmentAsOF(DateTimeImmutable $asOf): ?TariffSegment
    {
        foreach ($this->segments as $segment) {
            if ($segment->includes($asOf)) {
                return $segment;
            }
        }

        return null;
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
