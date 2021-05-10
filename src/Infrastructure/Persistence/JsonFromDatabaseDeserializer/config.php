<?php

namespace App\Infrastructure\Persistence\JsonFromDatabaseDeserializer;

use App\Infrastructure\ArrayKeysNameConverter\ArrayKeysNameConverter;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(JsonFromDatabaseDeserializer::class)
        ->args(
            [
                service('serializer.encoder.json'),
                service(ArrayKeysNameConverter::class)
            ]
        );
};
