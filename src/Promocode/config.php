<?php

namespace App\Promocode;

use App\Promocode\Model\Promocodes;
use App\Promocode\Query\GetPromocodeResources;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(Promocodes::class);

    $services->set(GetPromocodeResources::class); // TODO move to compiler pass ?
};