<?php

declare(strict_types=1);

namespace App\Order\Action\ApplyPromocode;

use App\Order\Model\Orders;

final class ApplyPromocodeHandler
{
    private $orders;

    public function __construct(Orders $orders)
    {
        $this->orders = $orders;
    }

    public function handle(ApplyPromocode $command): void
    {
        $order = $this->orders->getById($command->orderId, $command->eventId);

        $order->applyPromocode($command->promocodeId);
    }
}
