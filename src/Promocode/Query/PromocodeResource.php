<?php

declare(strict_types=1);

namespace App\Promocode\Query;

// TODO better names for return types for queries ?
use App\Infrastructure\ArrayComposer\Schema;
use App\Promocode\Model\Discount\Discount;

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

    public static function schema(): Schema
    {
        return new Schema(self::class);
    }
}
