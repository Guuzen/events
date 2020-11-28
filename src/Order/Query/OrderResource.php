<?php

declare(strict_types=1);

namespace App\Order\Query;

use Money\Money;

/**
 * @psalm-immutable
 */
final class OrderResource
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
    public $tariffId;

    /**
     * @var string
     */
    public $productType;

    /**
     * @var string
     */
    public $userId;

    /**
     * TODO new type money for resources ?
     *
     * @var Money
     */
    public $price;

    /**
     * @var Money
     */
    public $total;

    /**
     * @var \DateTimeImmutable
     */
    public $makedAt;

    /**
     * @var bool
     */
    public $paid;

    /**
     * @var bool
     */
    public $cancelled;

    /**
     * @var string|null
     */
    public $promocodeId;
}
