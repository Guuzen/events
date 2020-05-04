<?php

declare(strict_types=1);

namespace App\Product;

use App\Common\Error;
use App\Product\Action\SendTicket\SendTicket;
use App\Product\Action\SendTicket\SendTicketHandler;
use App\Product\Model\ProductDelivered;
use App\Product\Model\ProductType;
use App\Product\Model\TicketId;
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

        $ticketId = new TicketId((string)$productDelivered->productId);
        $error    = $this->sendTicketHandler->handle(new SendTicket($ticketId));
        if ($error instanceof Error) {
            $this->logger->error('send ticket failed', [
                'productId' => $productDelivered->productId,
                'error'     => \get_class($error),
            ]);
        }
    }
}
