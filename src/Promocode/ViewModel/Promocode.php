<?php

declare(strict_types=1);

namespace App\Promocode\ViewModel;

use App\Event\ViewModel\EventId;
use App\Promocode\ViewModel\AllowedTariffs\AllowedTariffs;
use App\Promocode\ViewModel\Discount\Discount;

/**
 * @psalm-immutable
 */
final class Promocode
{
    /**
     * @var PromocodeId
     */
    private $id;

    /**
     * @var EventId
     */
    private $eventId;

    /**
     * @var string
     */
    private $code;

    /**
     * @var Discount
     */
    private $discount;

    /**
     * @var int
     */
    private $useLimit;

    /**
     * @var \DateTimeImmutable
     */
    private $expireAt;

    /**
     * @var AllowedTariffs
     */
    private $allowedTariffs;

    /**
     * @var UsedInOrders
     */
    private $usedInOrders;

    /**
     * @var bool
     */
    private $usable;
}
