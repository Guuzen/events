<?php

declare(strict_types=1);

namespace App\Order\Action\PayByCard;

use App\Event\Model\EventId;
use App\Infrastructure\Http\AppController\AppController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PayByCardHttpAdapter extends AppController
{
    private $handler;

    public function __construct(PayByCardHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @Route("/order/payByCard", methods={"POST"})
     */
    public function payByCard(PayByCardRequest $payByCardRequest, EventId $eventId): Response
    {
        $paymentUrl = $this->handler->payByCard($payByCardRequest->toPayByCard($eventId));

        return $this->response($paymentUrl);
    }
}
