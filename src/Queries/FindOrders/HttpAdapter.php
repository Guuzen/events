<?php
declare(strict_types=1);

namespace App\Queries\FindOrders;

use App\Common\AppController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/orders")
 */
final class HttpAdapter extends AppController
{
    private $findOrders;

    public function __construct(FindOrders $findOrders)
    {
        $this->findOrders = $findOrders;
    }

    public function __invoke(Request $request): Response
    {
        $orders = ($this->findOrders)();

        return $this->successJson($orders);
    }
}
