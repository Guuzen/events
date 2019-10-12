<?php

namespace App\Tests\Contract\AppRequest\CreateFixedPromocode;

final class CreateFixedPromocode
{
    private $eventId;

    private $code;

    private $discount;

    private $useLimit;

    private $expireAt;

    private $allowedTariffs;

    private $usable;

    private function __construct(
        string $eventId,
        string $code,
        FixedDiscount $discount,
        int $useLimit,
        string $expireAt,
        array $allowedTariffs,
        bool $usable
    ) {
        $this->eventId        = $eventId;
        $this->code           = $code;
        $this->discount       = $discount;
        $this->useLimit       = $useLimit;
        $this->expireAt       = $expireAt;
        $this->allowedTariffs = $allowedTariffs;
        $this->usable         = $usable;
    }

    public static function any10RubWith(string $eventId, string $code, array $allowedTariffs): self
    {
        return new self($eventId, $code, FixedDiscount::is10Rub(), 1, '3000-12-12 00:00:00', $allowedTariffs, true);
    }
}
