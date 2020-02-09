<?php

namespace App\Product\Action;

use App\Infrastructure\Http\RequestResolver\AppRequest;

/**
 * @psalm-immutable
 */
final class DeliverProduct implements AppRequest
{
    public $productId;

    public function __construct(string $productId)
    {
        $this->productId = $productId;
    }
}
