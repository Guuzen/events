<?php

declare(strict_types=1);

namespace App\Promocode\Action\CreateFixedPromocode;

use App\Event\Model\EventId;
use App\Promocode\Model\AllowedTariffs\SpecificAllowedTariffs;
use App\Promocode\Model\Discount\FixedDiscount;

/**
 * @psalm-immutable
 */
final class CreateFixedPromocode
{
    public $eventId;

    public $code;

    public $discount;

    public $useLimit;

    public $expireAt;

    public $usable;

    public $allowedTariffIds;

    public function __construct(
        EventId $eventId,
        string $code,
        FixedDiscount $discount,
        int $useLimit,
        \DateTimeImmutable $expireAt,
        bool $usable,
        SpecificAllowedTariffs $allowedTariffIds
    )
    {
        $this->discount         = $discount;
        $this->code             = $code;
        $this->useLimit         = $useLimit;
        $this->expireAt         = $expireAt;
        $this->usable           = $usable;
        $this->allowedTariffIds = $allowedTariffIds;
        $this->eventId          = $eventId;
    }
}
