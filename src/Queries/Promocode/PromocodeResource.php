<?php

declare(strict_types=1);

namespace App\Queries\Promocode;

// TODO better names for return types for queries ?
use App\Infrastructure\ArrayComposer\Schema;
use App\Promocode\Model\Discount\Discount;
use App\Queries\Promocode\AllowedTariffs;

/**
 * @psalm-immutable
 */
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
     * @var \DateTimeImmutable
     */
    public $expireAt;

    /**
     * @var AllowedTariffs
     */
    public $allowedTariffs;

    /**
     * @var string[]
     */
    public $usedInOrders;

    /**
     * @var bool
     */
    public $usable;
}
