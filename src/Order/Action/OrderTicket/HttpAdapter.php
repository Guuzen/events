<?php

namespace App\Order\Action\OrderTicket;

use App\Common\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HttpAdapter extends BaseController
{
    public function __construct()
    {

    }

    /**
     * @Route("/order_ticket_by_wire", methods={"POST"})
     */
    public function orderTicketByWire(Request $request, OrderTicketByWire $orderTicketByWire, OrderTicketByWireHandler $handler): Response
    {
        [$result, $error] = $handler->handle($orderTicketByWire);

        if (!$error) {
            return $this->jsonSuccess();
        }

        return $this->jsonError($error);
    }
}
