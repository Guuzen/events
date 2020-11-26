<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Tariff\Model\Tariffs;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(Tariffs::class);
};