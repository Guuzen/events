<?php

namespace App\Tariff\Query;

/**
 * @psalm-immutable
 */
final class Tariff
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $eventId;

    /**
     * @var TariffPriceNet
     */
    private $priceNet;

    /**
     * @var ProductType
     */
    private $productType;
}
