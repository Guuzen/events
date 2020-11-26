<?php

namespace App\Infrastructure\Persistence\JsonFromDatabaseDeserializer;

use App\Infrastructure\ArrayKeysNameConverter\ArrayKeysNameConverter;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(JsonFromDatabaseDeserializer::class)
        ->args(
            [
                ref('serializer.encoder.json'),
                ref(ArrayKeysNameConverter::class)
            ]
        );
};
