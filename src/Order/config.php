<?php

namespace App\Order;

use App\Order\Model\Orders;
use App\Order\OnPromocodeEvent\OnPromocodeUsed;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(Orders::class);

    $services->set(OnPromocodeUsed::class)
        ->tag('app.notification_subscriber');
};