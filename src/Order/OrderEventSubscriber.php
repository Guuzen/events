<?php

namespace App\Order;

use App\Common\Error;
use App\Order\Model\OrderMarkedPaid;
use App\Product\Action\DeliverProduct;
use App\Product\Action\ProductHandler;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class OrderEventSubscriber implements EventSubscriberInterface
{
    private $productHandler;

    private $logger;

    public function __construct(ProductHandler $productHandler, LoggerInterface $logger)
    {
        $this->productHandler = $productHandler;
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
        $deliverProduct = new DeliverProduct((string) $orderMarkedPaid->productId);
        $error          = $this->productHandler->deliverProduct($deliverProduct);
        // TODO надо бы придумать как показывать ошибки пользователю в подобных ситуациях
        if ($error instanceof Error) {
            $this->logger->error('deliver product failed', [
                'productId' => $orderMarkedPaid->productId,
                'error'     => get_class($error),
            ]);
        }
    }
}
