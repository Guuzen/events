<?php

declare(strict_types=1);

namespace App\Order\Action\MarkOrderPaidByFondy;

use App\Event\Model\EventId;
use App\Infrastructure\Http\AppController\AppController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class MarkOrderPaidByFondyHttpAdapter extends AppController
{
    private $handler;

    public function __construct(MarkOrderPaidByFondyHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @Route("/order/{orderId}/markPaidByFondy", methods={"POST"})
     */
    public function markOrdePaidByFondy(MarkOrderPaidByFondyRequest $markOrderPaidByFondyRequest, EventId $eventId): Response
    {
        $this->handler->handle($markOrderPaidByFondyRequest->toMarkOrderPaidByFondy($eventId));

        $this->flush();

        return $this->response([]);
    }
}
