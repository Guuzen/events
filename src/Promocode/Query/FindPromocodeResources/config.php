<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Promocode\Query\FindPromocodeResources\FindPromocodeResources;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(FindPromocodeResources::class);
};