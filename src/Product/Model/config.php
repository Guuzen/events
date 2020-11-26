<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Product\Model\Tickets;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(Tickets::class);
};