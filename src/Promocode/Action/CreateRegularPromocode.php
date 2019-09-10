<?php

namespace App\Promocode\Action;

use App\Infrastructure\Http\AppRequest;

/**
 * @psalm-immutable
 */
final class CreateRegularPromocode implements AppRequest
{
    public $eventId;

    public $discount;

    public $useLimit;

    public $expireAt;

    public $usable;

    public $allowedTariffs;

    /**
     * @psalm-param array{
     *      amount: string,
     *      currency: string
     * } $discount
     *
     * @param string[] $allowedTariffs
     */
    public function __construct(
        string $eventId,
        array $discount,
        int $useLimit,
        string $expireAt,
        bool $usable,
        array $allowedTariffs = []
    ) {
        $this->discount       = $discount;
        $this->useLimit       = $useLimit;
        $this->expireAt       = $expireAt;
        $this->usable         = $usable;
        $this->allowedTariffs = $allowedTariffs;
        $this->eventId        = $eventId;
    }
}
