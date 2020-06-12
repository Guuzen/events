<?php

declare(strict_types=1);

namespace App\Order\Action\MarkOrderPaidByFondy;

use App\Common\Error;
use App\Order\Model\Orders;

final class MarkOrderPaidByFondyHandler
{
    private $orders;

    public function __construct(Orders $orders)
    {
        $this->orders = $orders;
    }

    public function handle(MarkOrderPaidByFondy $markOrderPaidByFondy): ?Error
    {
        $order = $this->orders->findById($markOrderPaidByFondy->orderId, $markOrderPaidByFondy->eventId);
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
