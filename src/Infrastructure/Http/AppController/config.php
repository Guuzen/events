<?php

namespace App\Infrastructure\Http\AppController;

use App\Infrastructure\Persistence\DatabaseSerializer\DatabaseSerializer;
use App\Infrastructure\Persistence\JsonFromDatabaseDeserializer\JsonFromDatabaseDeserializer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\ServiceLocator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services
        ->instanceof(AppController::class)
        ->call('setLocator', [ref(ServiceLocator::class)])
        ->tag('controller.service_arguments');

    $services->load('App\\', '../../../../src/**/*HttpAdapter.php');

    // TODO tags for resource providers by interface
    $services->set(ServiceLocator::class)
        ->args(
            [
                [
                    'serializer'                => ref('app.infrastructure.http.serializer'),
                    'databaseSerializer'        => ref(DatabaseSerializer::class),
                    'jsonFromDatabaseConverter' => ref(JsonFromDatabaseDeserializer::class),
                    'em'                        => ref(EntityManagerInterface::class),
                ],
            ]
        );
};

