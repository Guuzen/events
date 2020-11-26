<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Infrastructure\Persistence\DatabaseSerializer\DatabaseSerializer;
use App\Infrastructure\Persistence\DBALTypesInitializer\DBALTypes;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(DBALTypes::class)
        ->args([ref(DatabaseSerializer::class)]);
};
