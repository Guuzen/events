<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Event\Model\Events;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(Events::class);
};