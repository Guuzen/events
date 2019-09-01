<?php

namespace App\Tariff\Action;

use App\Infrastructure\Http\AppRequest;

final class CreateTariff implements AppRequest
{
    /**
     * @readonly
     */
    public $eventId;

    /**
     * @readonly
     */
    public $productType;

    /**
     * @readonly
     *
     * @var CreateTariffSegment[]
     */
    public $segments;

    /**
     * @param CreateTariffSegment[] $segments
     */
    public function __construct(string $eventId, string $productType, array $segments)
    {
        $this->eventId     = $eventId;
        $this->productType = $productType;
        $this->segments    = $segments;
    }
}
