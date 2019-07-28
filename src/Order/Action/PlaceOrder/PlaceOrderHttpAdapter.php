<?php

namespace App\Order\Action\PlaceOrder;

use App\Common\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PlaceOrderHttpAdapter extends BaseController
{
    public function __construct()
    {

    }

    /**
     * @Route("/order_ticket_by_wire", methods={"POST"})
     */
    public function orderTicketByWire(Request $request, PlaceOrder $placeOrder, PlaceOrderHandler $handler): Response
    {
        [$result, $error] = $handler->handle($placeOrder);

        if (!$error) {
            return $this->jsonSuccess();
        }

        return $this->jsonError($error);
    }
}
