<?php

declare(strict_types=1);

namespace App\Adapters\FondyApi\ConfirmOrderPayment;

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
     * @Route("/fondy/ticketOrder/{orderId}/confirmPayment", methods={"POST"})
     */
    public function __invoke(ConfirmOrderPaymentRequest $request, EventId $eventId): Response
    {
        $order = $this->ticketOrders->getById(new TicketOrderId($request->orderId), $eventId);

        $order->confirmPayment();

        $this->flush();

        return $this->response([]);
    }
}
