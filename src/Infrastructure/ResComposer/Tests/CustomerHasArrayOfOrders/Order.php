<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\CustomerHasArrayOfOrders;

use App\Infrastructure\ResComposer\Resource;

final class Order implements Resource
{
    public $id;
    public $price;

    public function __construct(string $id, string $price)
    {
        $this->id    = $id;
        $this->price = $price;
    }

    public function promises(): array
    {
        return [];
    }
}
