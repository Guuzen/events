<?php

namespace App\Order;

use App\Order\Action\ApplyPromocode\ApplyPromocode;
use App\Order\Action\ApplyPromocode\ApplyPromocodeHandler;
use App\Promocode\Model\PromocodeUsed;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class OrderSubscriber implements EventSubscriberInterface
{
    private $applyDiscountHandler;

    private $em;

    public function __construct(ApplyPromocodeHandler $applyDiscountHandler, EntityManagerInterface $em)
    {
        $this->applyDiscountHandler = $applyDiscountHandler;
        $this->em                   = $em;

    }

    public static function getSubscribedEvents(): array
    {
        return [
            PromocodeUsed::class => 'onPromocodeUsed',
        ];
    }

    public function onPromocodeUsed(PromocodeUsed $event): void
    {
        // TODO try catch + log is not helpful. There is must be user notification about error
        $this->applyDiscountHandler->handle(
            new ApplyPromocode(
                $event->eventId,
                $event->promocodeId,
                $event->orderId
            )
        );

        $this->em->flush();
    }
}
