<?php

namespace App\EventDomain;

use App\EventDomain\Queries\FindEventIdByDomain\FindEventIdByDomainQuery;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(FindEventIdByDomainQuery::class); // TODO rename to FindEventByIdHandler ?
};