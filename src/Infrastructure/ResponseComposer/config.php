<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\ApiGateway\Responses\PromocodeResponse;
use App\Infrastructure\ResponseComposer\ResourceProviders;
use App\Promocode\Query\FindPromocodeResources\FindPromocodeResources;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    // TODO collect in compiler pass?
    $services->set(ResourceProviders::class)
        ->args(
            [
                [
                    PromocodeResponse::class => ref(FindPromocodeResources::class),
                ]
            ]
        );
};
