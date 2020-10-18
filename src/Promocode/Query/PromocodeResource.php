<?php

declare(strict_types=1);

namespace App\Promocode\Query;

// TODO better names for return types for queries ?
use App\Promocode\Model\Discount\Discount;
use DateTimeImmutable;

final class PromocodeResource
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $eventId;

    /**
     * @var string
     */
    public $code;

    /**
     * @var Discount
     */
    public $discount;

    /**
     * @var int
     */
    public $useLimit;

    /**
     * @var DateTimeImmutable
     */
    public $expireAt;

    /**
     * @var array
     */
    public $allowedTariffs;

    /**
     * @var array
     */
    public $usedInOrders;

    /**
     * @var bool
     */
    public $usable;
}
