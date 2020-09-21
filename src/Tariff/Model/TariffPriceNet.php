<?php

namespace App\Tariff\Model;

use App\Infrastructure\InlineNormalizer\InlineDenormalizable;
use App\Infrastructure\InlineNormalizer\InlineNormalizable;
use App\Infrastructure\Persistence\DBALTypes\JsonDocumentType;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomTypeAnnotation as DBALType;
use App\Promocode\Model\Discount\Discount;
use App\Tariff\Model\Exception\TariffSegmentNotFound;
use App\Tariff\Model\Exception\TariffSegmentsCantIntersects;
use DateTimeImmutable;
use Exception;
use Money\Money;

// TODO цены должны не только не пересекаться, но ещё и идти всегда подряд?

/**
 * @DBALType(typeClass=JsonDocumentType::class)
 *
 * @InlineNormalizable()
 * @InlineDenormalizable()
 *
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

    public function calculateSum(Discount $discount, DateTimeImmutable $asOf): Money
    {
        $segment = $this->findSegmentAsOF($asOf);

        return $segment->calculateSum($discount);
    }

    private function findSegmentAsOF(DateTimeImmutable $asOf): TariffSegment
    {
        foreach ($this->segments as $segment) {
            if ($segment->includes($asOf)) {
                return $segment;
            }
        }

        throw new TariffSegmentNotFound('');
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
                    throw new TariffSegmentsCantIntersects('');
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
