<?php

namespace App\Infrastructure\ResponseComposer;

use App\ApiGateway\Responses\PromocodeResponse;
use App\Promocode\Query\FindPromocodeResources\FindPromocodeResources;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;

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
