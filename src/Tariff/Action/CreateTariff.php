<?php

namespace App\Tariff\Action;

use App\Infrastructure\Http\RequestResolver\AppRequest;
use App\Product\Model\ProductType;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @psalm-immutable
 */
final class CreateTariff implements AppRequest
{
    public $eventId;

    public $tariffType;

    /**
     * @var CreateTariffSegment[]
     */
    public $segments;

    /**
     * @Assert\NotBlank()
     *
     * @var ProductType $productType
     */
    public $productType;

    /**
     * @param CreateTariffSegment[] $segments
     */
    public function __construct(string $eventId, string $tariffType, array $segments, ProductType $productType)
    {
        $this->eventId     = $eventId;
        $this->tariffType  = $tariffType;
        $this->segments    = $segments;
        $this->productType = $productType;
    }
}
