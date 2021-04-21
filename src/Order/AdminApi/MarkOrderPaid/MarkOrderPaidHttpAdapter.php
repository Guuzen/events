<?php

namespace App\Order\AdminApi\MarkOrderPaid;

use App\Event\Model\EventId;
use App\Infrastructure\Http\AppController\AppController;
use App\Order\Model\OrderId;
use App\Order\Model\Orders;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class MarkOrderPaidHttpAdapter extends AppController
{
    private $orders;

    public function __construct(Orders $orders)
    {
        $this->orders = $orders;
    }

    /**
     * @Route("/admin/order/{orderId}/markPaid", methods={"POST"})
     */
    public function markOrderPaid(MarkOrderPaidRequest $request): Response
    {
        $order = $this->orders->getById(new OrderId($request->orderId), new EventId($request->eventId));

        $order->markPaid();

        $this->flush();

        return $this->validateResponse([]);
    }
}
