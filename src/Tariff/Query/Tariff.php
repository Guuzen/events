<?php

namespace App\Tariff\Query;

use App\Product\Model\ProductType;

/**
 * @psalm-immutable
 */
final class Tariff
{
    /**
     * TODO common id type
     *
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $eventId;

    /**
     * @var TariffPriceNet
     */
    public $priceNet;

    /**
     * TODO is this property belongs here ?
     *
     * @var ProductType
     */
    public $productType;

    public function __construct(string $id, string $eventId, TariffPriceNet $priceNet, ProductType $productType)
    {
        $this->id          = $id;
        $this->eventId     = $eventId;
        $this->priceNet    = $priceNet;
        $this->productType = $productType;
    }
}
