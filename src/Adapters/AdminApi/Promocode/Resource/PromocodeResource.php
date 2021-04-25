<?php

declare(strict_types=1);

namespace App\Adapters\AdminApi\Promocode\Resource;

// TODO better names for return types for queries ?
use App\Model\Promocode\Discount\Discount;

/**
 * TODO remove
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

    /**
     * @param string[] $usedInOrders
     */
    public function __construct(
        string $id,
        string $eventId,
        string $code,
        Discount $discount,
        int $useLimit,
        \DateTimeImmutable $expireAt,
        AllowedTariffs $allowedTariffs,
        array $usedInOrders,
        bool $usable
    )
    {
        $this->id             = $id;
        $this->eventId        = $eventId;
        $this->code           = $code;
        $this->discount       = $discount;
        $this->useLimit       = $useLimit;
        $this->expireAt       = $expireAt;
        $this->allowedTariffs = $allowedTariffs;
        $this->usedInOrders   = $usedInOrders;
        $this->usable         = $usable;
    }
}
