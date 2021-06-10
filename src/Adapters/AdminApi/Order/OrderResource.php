<?php

declare(strict_types=1);

namespace App\Adapters\AdminApi\Order;

use App\Adapters\AdminApi\Promocode\PromocodeResource;
use Guuzen\ResourceComposer\Config\DefaultMainResource;

final class OrderResource extends DefaultMainResource
{
    protected function config(): void
    {
        $this->hasOne(
            PromocodeResource::class,
            'promocode',
            'promocode',
        );
    }
}
