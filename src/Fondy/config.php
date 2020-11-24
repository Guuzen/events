<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Fondy\Fondy;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(Fondy::class);
};