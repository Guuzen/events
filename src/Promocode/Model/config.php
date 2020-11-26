<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Promocode\Model\Promocodes;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(Promocodes::class);
};