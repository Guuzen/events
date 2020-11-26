<?php

namespace App\Infrastructure\Persistence\DBALTypesInitializer;

use App\Infrastructure\Persistence\DatabaseSerializer\DatabaseSerializer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(DBALTypes::class)
        ->args([ref(DatabaseSerializer::class)]);
};
