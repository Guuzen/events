<?php

namespace App\Queries\Promocode\FindPromocodesInList;

use DateTimeImmutable;

/**
 * @psalm-immutable
 */
final class PromocodeInList
{
    public $id;

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

    public function __construct(
        string $id,
        string $code,
        FixedDiscount $discount,
        int $useLimit,
        DateTimeImmutable $expireAt,
        bool $usable
    ) {
        $this->id         = $id;
        $this->code       = $code;
        $this->discount   = $discount;
        $this->useLimit   = $useLimit;
        $this->expireAt   = $expireAt;
        $this->usable     = $usable;
    }
}
