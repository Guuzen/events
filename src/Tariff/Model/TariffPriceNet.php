<?php

namespace App\Tariff\Model;

use App\Common\Error;
use App\Infrastructure\Persistence\DBALTypes\JsonDocumentType;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomTypeAnnotation as DoctrineType;
use App\Promocode\Model\Discount\Discount;
use App\Tariff\Model\Error\TariffSegmentNotFound;
use App\Tariff\Model\Exception\TariffSegmentsCantIntersects;
use DateTimeImmutable;
use Exception;
use Money\Money;

// TODO цены должны не только не пересекаться, но ещё и идти всегда подряд?

/**
 * @DoctrineType(typeClass=JsonDocumentType::class)
 * @psalm-immutable
 */
final class TariffPriceNet
{
    /**
     * @var TariffSegment[]
     */
    private $segments;

    /**
     * @param TariffSegment[] $segments
     */
    public function __construct(array $segments)
    {
        static::assertSegmentsNotEmpty($segments);
        static::assertPricesNotIntersects($segments);
        $this->segments = $segments;
    }

    /**
     * @return Money|TariffSegmentNotFound
     */
    public function calculateSum(Discount $discount, DateTimeImmutable $asOf)
    {
        $segment = $this->findSegmentAsOF($asOf);
        if ($segment instanceof Error) {
            return $segment;
        }

        return $segment->calculateSum($discount);
    }

    /**
     * @return TariffSegment|TariffSegmentNotFound
     */
    private function findSegmentAsOF(DateTimeImmutable $asOf)
    {
        foreach ($this->segments as $segment) {
            if ($segment->includes($asOf)) {
                return $segment;
            }
        }

        return new TariffSegmentNotFound();
    }

    /**
     * @param TariffSegment[] $segments
     */
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
            throw new Exception('segments should not be empty');
        }
    }
}
