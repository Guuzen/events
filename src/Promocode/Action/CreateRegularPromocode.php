<?php

namespace App\Promocode\Action;

use App\Common\AppRequest;

final class CreateRegularPromocode implements AppRequest
{
    /**
     * @readonly
     */
    public $eventId;

    /**
     * @psalm-var array{
     *      amount: string,
     *      currency: string,
     * }
     */
    public $discount;

    /**
     * @readonly
     */
    public $useLimit;

    /**
     * @readonly
     */
    public $expireAt;

    /**
     * @readonly
     */
    public $usable;

    /**
     * @readonly
     * @var string[]
     */
    public $allowedTariffs;

    /**
     * @readonly
     * @psalm-param array{
     *      amount: string,
     *      currency: string
     * } $discount
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
