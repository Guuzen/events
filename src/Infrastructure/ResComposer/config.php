<?php

namespace App\Infrastructure\Persistence\DatabaseSerializer;

use App\Adapters\AdminApi\Promocode\PromocodeLoader;
use App\Infrastructure\ResComposer\Config\MainResource;
use App\Infrastructure\ResComposer\Config\RelatedResource;
use App\Infrastructure\ResComposer\Link\OneToOne;
use App\Infrastructure\ResComposer\PromiseCollector\SimpleCollector;
use App\Infrastructure\ResComposer\ResourceComposer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\inline;
use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(ResourceComposer::class)
        ->call(
            'registerRelation', [
                inline(MainResource::class)->args(
                    [
                        'order',
                        inline(SimpleCollector::class)->args(['promocodeId', 'promocode']),
                    ]
                ),
                inline(OneToOne::class),
                inline(RelatedResource::class)->args(
                    [
                        'promocode',
                        'id',
                        ref(PromocodeLoader::class),
                    ]
                ),
            ]
        );
};
