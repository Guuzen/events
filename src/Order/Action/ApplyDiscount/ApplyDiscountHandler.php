<?php

declare(strict_types=1);

namespace App\Order\Action\ApplyDiscount;

use App\Common\Error;
use App\Order\Model\Orders;

final class ApplyDiscountHandler
{
    private $orders;

    public function __construct(Orders $orders)
    {
        $this->orders = $orders;
    }

    public function handle(ApplyDiscount $command): ?Error
    {
        $order = $this->orders->findById($command->orderId, $command->eventId);
        if ($order instanceof Error) {
            return $order;
        }

        $order->applyDiscount($command->discount);

        return null;
    }
}
