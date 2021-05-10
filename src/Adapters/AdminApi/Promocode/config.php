<?php

namespace App\Adapters\AdminApi\Promocode;

use App\Infrastructure\Persistence\ResourceComposer\PostgresLoader;
use Guuzen\ResourceComposer\Config\MainResource;
use Guuzen\ResourceComposer\Config\RelatedResource;
use Guuzen\ResourceComposer\Link\OneToOne;
use Guuzen\ResourceComposer\PromiseCollector\SimpleCollector;
use Guuzen\ResourceComposer\ResourceComposer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\inline_service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set('app.admin_api.promocode.resource_loader', PostgresLoader::class)->args(
        [
            '$table'       => 'promocode',
            '$searchField' => 'id',
            '$fields'      => ['id', 'discount'],
        ]
    );

    $services->set(ResourceComposer::class)
        ->call(
            'registerRelation', [
                inline_service(MainResource::class)->args(
                    [
                        'order',
                        inline_service(SimpleCollector::class)->args(['promocode', 'promocode']),
                    ]
                ),
                inline_service(OneToOne::class),
                inline_service(RelatedResource::class)->args(
                    [
                        'promocode',
                        'id',
                        service('app.admin_api.promocode.resource_loader'),
                    ]
                ),
            ]
        );
};
