<?php

declare(strict_types=1);

namespace App\Adapters\ClientApi\TicketOrder\PayByCard;

use App\Infrastructure\Http\AppController\AppController;
use App\Integrations\Fondy\FondyClient;
use App\Model\Event\EventId;
use App\Model\TicketOrder\TicketOrderId;
use App\Model\TicketOrder\TicketOrders;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PayByCardHttpAdapter extends AppController
{
    private $ticketOrders;

    private $fondy;

    public function __construct(TicketOrders $ticketOrders, FondyClient $fondy)
    {
        $this->ticketOrders = $ticketOrders;
        $this->fondy        = $fondy;
    }

    /**
     * @Route("/ticketOrder/{orderId}/payByCard", methods={"POST"})
     */
    public function payByCard(PayByCardRequest $request, EventId $eventId): Response
    {
        $order = $this->ticketOrders->getById(new TicketOrderId($request->orderId), $eventId);

        $paymentUrl = $order->createFondyPayment($this->fondy);

        return $this->validateResponse($paymentUrl);
    }
}
