<?php

declare(strict_types=1);

namespace App\Adapters\ClientApi\Order\PayByCard;

use App\Model\Event\EventId;
use App\Integrations\Fondy\FondyClient;
use App\Infrastructure\Http\AppController\AppController;
use App\Model\Order\OrderId;
use App\Model\Order\Orders;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PayByCardHttpAdapter extends AppController
{
    private $orders;

    private $fondy;

    public function __construct(Orders $orders, FondyClient $fondy)
    {
        $this->orders = $orders;
        $this->fondy  = $fondy;
    }

    /**
     * @Route("/order/{orderId}/payByCard", methods={"POST"})
     */
    public function payByCard(PayByCardRequest $request, EventId $eventId): Response
    {
        $order = $this->orders->getById(new OrderId($request->orderId), $eventId);

        $paymentUrl = $order->createFondyPayment($this->fondy);

        return $this->validateResponse($paymentUrl);
    }
}
