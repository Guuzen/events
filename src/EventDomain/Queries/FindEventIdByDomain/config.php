<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\EventDomain\Queries\FindEventIdByDomain\FindEventIdByDomainQuery;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(FindEventIdByDomainQuery::class); // TODO rename to FindEventByIdHandler ?
};