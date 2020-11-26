<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Order\Model\Orders;
use App\Order\OrderSubscriber;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(Orders::class);

    $services->set(OrderSubscriber::class)
        ->tag('app.notification_subscriber');
};