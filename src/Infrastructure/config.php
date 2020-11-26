<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Infrastructure\FixPostgreSQLDefaultSchemaListener;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(FixPostgreSQLDefaultSchemaListener::class)
        ->tag('doctrine.event_listener', ['event' => 'postGenerateSchema']);
};