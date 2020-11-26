<?php

namespace App\Infrastructure\ArrayKeysNameConverter;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;

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