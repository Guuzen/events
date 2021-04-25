<?php

declare(strict_types=1);

namespace App\Model\Order\OnPromocodeEvent;

use App\Model\Order\Orders;
use App\Model\Promocode\PromocodeUsed;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

final class OnPromocodeUsed implements MessageSubscriberInterface
{
    private $em;

    private $orders;

    public function __construct(EntityManagerInterface $em, Orders $orders)
    {
        $this->em     = $em;
        $this->orders = $orders;
    }

    public static function getHandledMessages(): iterable
    {
        yield PromocodeUsed::class => [
            'method' => 'applyPromocode',
        ];
    }

    public function applyPromocode(PromocodeUsed $event): void
    {
        $order = $this->orders->getById($event->orderId, $event->eventId);

        $order->applyPromocode($event->promocodeId);

        $this->em->flush();
    }
}
