<?php

namespace App\Promocode;

use App\Promocode\Frontend\OrderHasOnePromocode;
use App\Promocode\Frontend\PromocodeLoader;
use App\Promocode\Model\Promocodes;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(Promocodes::class);

    $services->set(OrderHasOnePromocode::class);

    $services->set(PromocodeLoader::class);
};
