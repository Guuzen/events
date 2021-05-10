<?php

declare(strict_types=1);

namespace App\Adapters\ClientApi\TicketOrder\ApplyPromocode;

use App\Infrastructure\Http\AppController\AppController;
use App\Model\Event\EventId;
use App\Model\Promocode\Promocodes;
use App\Model\TicketOrder\TicketOrderId;
use App\Model\TicketOrder\TicketOrders;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ApplyPromocodeHttpAdapter extends AppController
{
    private $promocodes;

    private $ticketOrders;

    public function __construct(Promocodes $promocodes, TicketOrders $ticketOrders)
    {
        $this->promocodes   = $promocodes;
        $this->ticketOrders = $ticketOrders;
    }

    /**
     * @Route("/ticketOrder/applyPromocode", methods={"POST"})
     */
    public function __invoke(ApplyPromocodeRequest $request, EventId $eventId): Response
    {
        $promocode = $this->promocodes->getByCode($request->code, $eventId);
        $order     = $this->ticketOrders->getById(new TicketOrderId($request->orderId), $eventId);

        $order->applyPromocode($promocode);

        $this->flush();

        return $this->response([]);
    }
}
