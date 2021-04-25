<?php

namespace App\Model\Product;

use App\Model\Product\OnOrderEvent\OnOrderMarkedPaid;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(Tickets::class);

    $services->set(OnOrderMarkedPaid::class);
};