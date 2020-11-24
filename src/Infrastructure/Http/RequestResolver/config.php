<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Infrastructure\Http\RequestResolver\AppRequestResolver;
use App\Infrastructure\Http\RequestResolver\InvalidAppRequestListener;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    // LISTENER FOR VALIDATION ERRORS HANDLING
    $services->set(InvalidAppRequestListener::class)->tag('kernel.event_listener', ['event' => 'kernel.exception']);

    // RESOLVER FOR INJECTING APP REQUESTS AS HTTP ADAPTERS METHODS ARGUMENTS
    $services->set(AppRequestResolver::class)
        ->args(['$serializer' => ref('app.infrastructure.http.serializer')]);
};