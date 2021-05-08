<?php

namespace App\Adapters\AdminApi\TicketOrder\ConfirmOrderPayment;

use App\Infrastructure\Http\AppController\AppController;
use App\Model\Event\EventId;
use App\Model\TicketOrder\TicketOrderId;
use App\Model\TicketOrder\TicketOrders;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ConfirmOrderPaymentHttpAdapter extends AppController
{
    private $ticketOrders;

    public function __construct(TicketOrders $ticketOrders)
    {
        $this->ticketOrders = $ticketOrders;
    }

    /**
     * @Route("/admin/ticketOrder/confirmPayment", methods={"POST"})
     */
    public function __invoke(ConfirmOrderPaymentRequest $request): Response
    {
        $order = $this->ticketOrders->getById(new TicketOrderId($request->orderId), new EventId($request->eventId));

        $order->confirmPayment();

        $this->flush();

        return $this->validateResponse([]);
    }
}
