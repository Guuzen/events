<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Infrastructure\ArrayKeysNameConverter\ArrayKeysNameConverter;
use App\Infrastructure\Persistence\JsonFromDatabaseDeserializer\JsonFromDatabaseDeserializer;

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
