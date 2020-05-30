<?php

namespace App\Queries\Promocode\FindPromocodesInList;

use DateTimeImmutable;

/**
 * @psalm-immutable
 */
final class PromocodeInList
{
    public $id;

    public $eventId;

    public $code;

    /**
     * @var FixedDiscount
     */
    public $discount;

    public $useLimit;

    /**
     * @var DateTimeImmutable
     */
    public $expireAt;

    public $usable;

    public $usedInOrders;

    public $allowedTariffs;

    public function __construct(
        string $id,
        string $eventId,
        string $code,
        FixedDiscount $discount,
        int $useLimit,
        DateTimeImmutable $expireAt,
        bool $usable,
        array $usedInOrders,
        array $allowedTariffs
    )
    {
        $this->id             = $id;
        $this->eventId        = $eventId;
        $this->code           = $code;
        $this->discount       = $discount;
        $this->useLimit       = $useLimit;
        $this->expireAt       = $expireAt;
        $this->usable         = $usable;
        $this->usedInOrders   = $usedInOrders;
        $this->allowedTariffs = $allowedTariffs;
    }
}
