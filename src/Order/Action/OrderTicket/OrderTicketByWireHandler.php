<?php

namespace App\Order\Action\OrderTicket;

final class OrderTicketByWireHandler
{
    public function handle(OrderTicketByWire $orderTicketByWire): array
    {
        return ['result', 'error'];
    }
}
