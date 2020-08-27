<?php

declare(strict_types=1);

namespace App\Order\Action\ApplyDiscount;

use App\Order\Model\Orders;

final class ApplyDiscountHandler
{
    private $orders;

    public function __construct(Orders $orders)
    {
        $this->orders = $orders;
    }

    public function handle(ApplyDiscount $command): void
    {
        $order = $this->orders->getById($command->orderId, $command->eventId);

        $order->applyDiscount($command->discount);
    }
}
