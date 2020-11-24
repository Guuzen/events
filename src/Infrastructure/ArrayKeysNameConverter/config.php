<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Infrastructure\ArrayKeysNameConverter\ArrayKeysNameConverter;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(ArrayKeysNameConverter::class)
        ->args(
            [
                ref('serializer.name_converter.camel_case_to_snake_case'),
            ]
        );
};