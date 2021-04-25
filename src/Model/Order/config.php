<?php

namespace App\Model\Order;

use App\Model\Order\OnPromocodeEvent\OnPromocodeUsed;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(Orders::class);

    $services->set(OnPromocodeUsed::class);

};