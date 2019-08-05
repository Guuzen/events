<?php

namespace App\Queries\FindOrders;

use App\Common\AppController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/order/list")
 */
final class FindOrdersHttpAdapter extends AppController
{
    private $findOrders;

    public function __construct(FindOrders $findOrders)
    {
        $this->findOrders = $findOrders;
    }

    public function __invoke(Request $request): Response
    {
        $orders = ($this->findOrders)($request->get('eventId'));

        return $this->successJson($orders);
    }
}
