<?php

declare(strict_types=1);

namespace App\Adapters\ClientApi\TicketOrder\ApplyPromocode;

use App\Infrastructure\Http\RequestResolver\AppRequest;

/**
 * @psalm-immutable
 */
final class ApplyPromocodeRequest implements AppRequest
{
    public $orderId;

    public $code;

    public function __construct(string $orderId, string $code)
    {
        $this->orderId = $orderId;
        $this->code    = $code;
    }
}
