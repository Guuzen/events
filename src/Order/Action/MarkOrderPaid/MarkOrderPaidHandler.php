<?php

namespace App\Order\Action\MarkOrderPaid;

use App\Common\Error;
use App\Order\Model\Orders;

final class MarkOrderPaidHandler
{
    private $orders;

    public function __construct(Orders $orders)
    {
        $this->orders = $orders;
    }

    public function markOrderPaid(MarkOrderPaid $command): ?Error
    {
        $order = $this->orders->findById($command->orderId, $command->eventId);
        if ($order instanceof Error) {
            return $order;
        }

        $markPaidError = $order->markPaid();
        if ($markPaidError instanceof Error) {
            return $markPaidError;
        }

        return null;
    }
}
