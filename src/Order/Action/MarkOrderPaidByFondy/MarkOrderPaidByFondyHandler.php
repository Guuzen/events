<?php

declare(strict_types=1);

namespace App\Order\Action\MarkOrderPaidByFondy;

use App\Order\Model\Orders;

final class MarkOrderPaidByFondyHandler
{
    private $orders;

    public function __construct(Orders $orders)
    {
        $this->orders = $orders;
    }

    public function handle(MarkOrderPaidByFondy $markOrderPaidByFondy): void
    {
        $order = $this->orders->getById($markOrderPaidByFondy->orderId, $markOrderPaidByFondy->eventId);

        $order->markPaid();
    }
}
