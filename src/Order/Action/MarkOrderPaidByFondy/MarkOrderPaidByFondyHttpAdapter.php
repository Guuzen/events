<?php

declare(strict_types=1);

namespace App\Order\Action\MarkOrderPaidByFondy;

use App\Event\Model\EventId;
use App\Infrastructure\Http\AppController\AppController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class MarkOrderPaidByFondyHttpAdapter extends AppController
{
    private $handler;

    private $em;

    public function __construct(MarkOrderPaidByFondyHandler $handler, EntityManagerInterface $em)
    {
        $this->handler = $handler;
        $this->em      = $em;
    }

    /**
     * @Route("/order/markPaidByFondy", methods={"POST"})
     */
    public function markOrdePaidByFondy(MarkOrderPaidByFondyRequest $markOrderPaidByFondyRequest, EventId $eventId): Response
    {
        $result = $this->handler->handle($markOrderPaidByFondyRequest->toMarkOrderPaidByFondy($eventId));

        $this->em->flush();

        return $this->response($result);
    }
}
