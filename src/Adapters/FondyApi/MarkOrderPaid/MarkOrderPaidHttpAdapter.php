<?php

declare(strict_types=1);

namespace App\Adapters\FondyApi\MarkOrderPaid;

use App\Model\Event\EventId;
use App\Infrastructure\Http\AppController\AppController;
use App\Model\Order\OrderId;
use App\Model\Order\Orders;
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
     * @Route("/fondy/{orderId}/markPaid", methods={"POST"})
     */
    public function markOrderPaid(MarkOrderPaidRequest $request, EventId $eventId): Response
    {
        $order = $this->orders->getById(new OrderId($request->orderId), $eventId);

        $order->markPaid();

        $this->flush();

        return $this->validateResponse([]);
    }
}
