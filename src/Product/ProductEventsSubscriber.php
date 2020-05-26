<?php

declare(strict_types=1);

namespace App\Product;

use App\Product\Action\SendTicket\SendTicketHandler;
use App\Product\Model\ProductDelivered;
use App\Product\Model\ProductType;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ProductEventsSubscriber implements EventSubscriberInterface
{
    private $sendTicketHandler;

    private $logger;

    public function __construct(SendTicketHandler $sendTicketHandler, LoggerInterface $logger)
    {
        $this->sendTicketHandler = $sendTicketHandler;
        $this->logger            = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ProductDelivered::class => 'onProductDeliveredSendTicket',
        ];
    }

    public function onProductDeliveredSendTicket(ProductDelivered $productDelivered): void
    {
        if ($productDelivered->productType->equals(ProductType::ticket()) === false) {
            return;
        }
    }
}
