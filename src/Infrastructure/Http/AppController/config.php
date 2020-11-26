<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Infrastructure\Http\AppController\AppController;
use App\Infrastructure\Persistence\JsonFromDatabaseDeserializer\JsonFromDatabaseDeserializer;
use App\Infrastructure\ResponseComposer\ResourceProviders;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ServiceLocator;

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
                    'jsonFromDatabaseConverter' => ref(JsonFromDatabaseDeserializer::class),
                    'resourceProviders'         => ref(ResourceProviders::class),
                    'em'                        => ref(EntityManagerInterface::class),
                ],
            ]
        );
};

