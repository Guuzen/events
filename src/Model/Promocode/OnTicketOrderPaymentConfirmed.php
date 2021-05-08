<?php

declare(strict_types=1);

namespace App\Model\Promocode;

use App\Model\TicketOrder\TicketOrderPaymentConfirmed;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

final class OnTicketOrderPaymentConfirmed implements MessageSubscriberInterface
{
    private $promocodes;

    private $em;

    public function __construct(Promocodes $promocodes, EntityManagerInterface $em)
    {
        $this->promocodes = $promocodes;
        $this->em         = $em;
    }

    public static function getHandledMessages(): iterable
    {
        yield TicketOrderPaymentConfirmed::class => [
            'method' => 'usePromocode',
        ];
    }

    public function usePromocode(TicketOrderPaymentConfirmed $event): void
    {
        if ($event->promocodeId === null) {
            return;
        }

        $promocode = $this->promocodes->getById($event->promocodeId, $event->eventId);

        $promocode->use($event->orderId);

        $this->em->flush();
    }
}
