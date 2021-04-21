<?php

namespace App\Infrastructure\Persistence\DatabaseSerializer;

use App\Infrastructure\ResComposer\Link\OneToOne;
use App\Infrastructure\ResComposer\PromiseCollector\SimpleCollector;
use App\Infrastructure\ResComposer\ResourceComposer;
use App\Promocode\AdminApi\PromocodeLoader;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\inline;
use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(ResourceComposer::class)
        ->call('registerLoader', [ref(PromocodeLoader::class)])
        ->call(
            'registerConfig', [
                'order',
                inline(OneToOne::class)->args(['id']),
                'promocode',
                PromocodeLoader::class,
                inline(SimpleCollector::class)->args(['promocodeId', 'promocode']),
            ]
        );
};
