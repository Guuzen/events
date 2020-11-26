<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\TariffDescription\TariffDescriptions;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(TariffDescriptions::class);
};