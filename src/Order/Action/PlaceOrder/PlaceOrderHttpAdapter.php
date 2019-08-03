<?php

namespace App\Order\Action\PlaceOrder;

use App\Common\BaseController;
use App\Queries\FindEventIdByDomain;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// TODO rename to httpadapter
final class PlaceOrderHttpAdapter extends BaseController
{
    /**
     * @var FindEventIdByDomain
     */
    private $findEventIdByDomain;

    public function __construct(FindEventIdByDomain $findEventIdByDomain)
    {
        $this->findEventIdByDomain = $findEventIdByDomain;
    }

    /**
     * @Route("/order_ticket_by_wire", methods={"POST"})
     */
    public function orderTicketByWire(Request $request, PlaceOrder $placeOrder, PlaceOrderHandler $handler): Response
    {
        $domain              = $request->getHost();
        $placeOrder->eventId = ($this->findEventIdByDomain)($domain);
        [$result, $error]    = $handler->handle($placeOrder);

        if (!$error) {
            // TODO cleanup
            return $this->jsonSuccess();
        }

        return $this->jsonError($error);
    }
}
