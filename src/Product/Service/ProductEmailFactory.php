<?php

namespace App\Product\Service;

use App\Product\Model\ProductId;
use App\Product\Service\Error\ProductEmailNotFound;
use Swift_Message;

interface ProductEmailFactory
{
    /**
     * @return Swift_Message|ProductEmailNotFound
     */
    public function create(ProductId $productId);
}
