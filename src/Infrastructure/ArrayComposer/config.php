<?php

namespace App\Infrastructure\ArrayComposer;

use App\Promocode\Query\GetPromocodeResources;
use App\Promocode\Query\PromocodeResource;
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
                    PromocodeResource::class => ref(GetPromocodeResources::class),
                ]
            ]
        );
};
