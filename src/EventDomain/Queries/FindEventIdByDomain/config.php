<?php

namespace App\EventDomain\Queries\FindEventIdByDomain;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(FindEventIdByDomainQuery::class); // TODO rename to FindEventByIdHandler ?
};