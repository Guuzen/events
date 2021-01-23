<?php

namespace App\Order\AdminApi\GetOrderById;

use App\Infrastructure\Http\RequestResolver\AppRequest;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @psalm-immutable
 */
final class GetOrderByIdRequest implements AppRequest
{
    /**
     * @Assert\Uuid()
     */
    public $orderId;

    public function __construct(string $orderId)
    {
        $this->orderId = $orderId;
    }
}
