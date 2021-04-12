<?php

namespace App\Order;

use App\Infrastructure\ResComposer\ResourceResolver;
use App\Order\AdminApi\OrderResource;
use App\Order\Model\Orders;
use App\Order\OnPromocodeEvent\OnPromocodeUsed;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(Orders::class);

    $services->set(OnPromocodeUsed::class);

    $services->set('app.order_has_promocode', ResourceResolver::class)
        ->factory([OrderResource::class, 'create']);
};