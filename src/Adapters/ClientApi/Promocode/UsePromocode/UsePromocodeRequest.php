<?php

declare(strict_types=1);

namespace App\Adapters\ClientApi\Promocode\UsePromocode;

use App\Infrastructure\Http\RequestResolver\AppRequest;

/**
 * @psalm-immutable
 */
final class UsePromocodeRequest implements AppRequest
{
    public $orderId;

    public $code;

    public $tariffId;

    public function __construct(string $orderId, string $code, string $tariffId)
    {
        $this->orderId  = $orderId;
        $this->code     = $code;
        $this->tariffId = $tariffId;
    }
}
