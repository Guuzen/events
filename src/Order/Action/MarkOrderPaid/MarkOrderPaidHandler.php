<?php

namespace App\Order\Action\MarkOrderPaid;

use App\Order\Model\Orders;

final class MarkOrderPaidHandler
{
    private $orders;

    public function __construct(Orders $orders)
    {
        $this->orders = $orders;
    }

    public function markOrderPaid(MarkOrderPaid $command): void
    {
        $order = $this->orders->getById($command->orderId, $command->eventId);

        $order->markPaid();
    }
}
