<?php

namespace App\Order;

use App\Common\Error;
use App\Order\Model\OrderMarkedPaid;
use App\Product\Action\DeliverProduct;
use App\Product\Action\ProductHandler;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class OrderNotificationSubscriber implements EventSubscriberInterface
{
    private $productHandler;

    private $logger;

    private $em;

    public function __construct(ProductHandler $productHandler, EntityManagerInterface $em, LoggerInterface $logger)
    {
        $this->productHandler = $productHandler;
        $this->em             = $em;
        $this->logger         = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OrderMarkedPaid::class => 'onOrderMarkedPaid',
        ];
    }

    public function onOrderMarkedPaid(OrderMarkedPaid $orderMarkedPaid): void
    {
        $deliverProduct = new DeliverProduct((string)$orderMarkedPaid->productId);
        $error          = $this->productHandler->deliverProduct($deliverProduct);
        // TODO move to exceptions
        if ($error instanceof Error) {
            $this->logger->error('deliver product failed', [
                'productId' => $orderMarkedPaid->productId,
                'error'     => \get_class($error),
            ]);
        }
        $this->em->flush();
    }
}
