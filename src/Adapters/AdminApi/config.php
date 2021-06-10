<?php

namespace App\Adapters\AdminApi;

use App\Adapters\AdminApi\Order\OrderResource;
use App\Adapters\AdminApi\Promocode\PromocodeResource;
use Guuzen\ResourceComposer\ResourceComposer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(OrderResource::class);

    $services->set(PromocodeResource::class)->arg('$connection', service('doctrine.dbal.default_connection'));

    $services->set(ResourceComposer::class)
        ->call('registerMainResource', [service(OrderResource::class)])
        ->call('registerRelatedResource', [service(PromocodeResource::class)]);
};
