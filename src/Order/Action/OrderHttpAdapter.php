<?php

namespace App\Order\Action;

use App\Common\BaseController;
use App\Queries\FindEventIdByDomain;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class OrderHttpAdapter extends BaseController
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
        $domain              = $request->getHost();
        $placeOrder->eventId = ($this->findEventIdByDomain)($domain);
        [$result, $error]    = $this->handler->placeOrder($placeOrder);

        if (null !== $error) {
            return $this->jsonError($error);
        }

        return $this->jsonSuccess();
    }
}
