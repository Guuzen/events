<?php

namespace App\Adapters\AdminApi\Promocode\CreateTariffPromocode;

use App\Infrastructure\Http\RequestResolver\AppRequest;

/**
 * @psalm-immutable
 */
final class CreateTariffPromocodeRequest implements AppRequest
{
    public $eventId;

    public $code;

    public $discount;

    public $useLimit;

    public $expireAt;

    public $usable;

    public $allowedTariffIds;

    /**
     * @psalm-param array{
     *      amount: string,
     *      currency: string
     * } $discount
     *
     * @param string[] $allowedTariffIds
     */
    public function __construct(
        string $eventId,
        string $code,
        array $discount,
        int $useLimit,
        string $expireAt,
        bool $usable,
        array $allowedTariffIds = []
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
