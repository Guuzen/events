<?php

namespace App\Order\Action;

use App\Common\AppController;
use App\Queries\FindEventIdByDomain;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class OrderHttpAdapter extends AppController
{
    private $findEventIdByDomain;

    private $handler;

    public function __construct(FindEventIdByDomain $findEventIdByDomain, OrderHandler $handler)
    {
        $this->findEventIdByDomain = $findEventIdByDomain;
        $this->handler             = $handler;
    }

    /**
     * @Route("/order/place", methods={"POST"})
     */
    public function placeOrder(Request $request, PlaceOrder $placeOrder): Response
    {
        $domain               = $request->getHost();
        $placeOrder->eventId  = ($this->findEventIdByDomain)($domain);
        [$orderId, $error]    = $this->handler->placeOrder($placeOrder);

        if (null !== $error) {
            return $this->errorJson($error);
        }

        return $this->successJson($orderId);
    }

    /**
     * @Route("/admin/order/mark_paid", methods={"POST"})
     */
    public function markOrderPaid(MarkOrderPaid $markOrderPaid): Response
    {


        return $this->successJson();
    }
}
