<?php

namespace App\Product\Service;

use App\Common\Error;
use App\Product\Model\ProductId;
use App\Product\Model\ProductType;
use App\Product\Service\Error\ProductEmailNotFound;
use App\Product\Service\Error\ProductNotDelivered;
use Swift_Mailer;

class ProductEmailDelivery
{
    private $mailer;

    private $productEmailFactoryDispatcher;

    public function __construct(Swift_Mailer $mailer, ProductEmailFactoryDispatcher $productEmailFactoryDispatcher)
    {
        $this->mailer                        = $mailer;
        $this->productEmailFactoryDispatcher = $productEmailFactoryDispatcher;
    }

    /**
     * @return ProductEmailNotFound|ProductNotDelivered|null
     */
    public function deliver(ProductId $ticketId, ProductType $productType)
    {
        $productEmailFactory = $this->productEmailFactoryDispatcher->dispatch($productType);
        $productEmail        = $productEmailFactory->create($ticketId);
        if ($productEmail instanceof Error) {
            return $productEmail;
        }

        $sent = $this->mailer->send($productEmail);
        if (0 === $sent) {
            return new ProductNotDelivered();
        }

        return null;
    }
}
