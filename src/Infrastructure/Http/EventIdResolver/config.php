<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Infrastructure\Http\EventIdResolver\EventIdResolver;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);


    $services->set(EventIdResolver::class);
};
