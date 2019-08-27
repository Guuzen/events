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
     */
    public $priceNet;

    public function __construct(string $eventId, string $productType, CreateTariffPriceNet $priceNet)
    {
        $this->eventId     = $eventId;
        $this->productType = $productType;
        $this->priceNet    = $priceNet;
    }
}
