<?php

namespace App\Infrastructure\Http\AppController;

use App\Infrastructure\Http\Openapi\OpenapiSchema;
use App\Infrastructure\Http\Openapi\OpenapiValidator;
use App\Infrastructure\Persistence\DatabaseSerializer\DatabaseSerializer;
use App\Infrastructure\Persistence\JsonFromDatabaseDeserializer\JsonFromDatabaseDeserializer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\HttpFoundation\RequestStack;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services
        ->instanceof(AppController::class)
        ->call('setLocator', [service(ServiceLocator::class)])
        ->tag('controller.service_arguments');

    $services->load('App\\', '../../../../src/**/*HttpAdapter.php');

    // TODO tags for resource providers by interface
    $services->set(ServiceLocator::class)
        ->args(
            [
                [
                    'serializer'                => service('app.infrastructure.http.serializer'),
                    'databaseSerializer'        => service(DatabaseSerializer::class),
                    'jsonFromDatabaseConverter' => service(JsonFromDatabaseDeserializer::class),
                    'em'                        => service(EntityManagerInterface::class),
                    'requestStack'              => service(RequestStack::class),
                    'openapiValidator'          => service(OpenapiValidator::class)
                ],
            ]
        );
};

