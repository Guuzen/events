<?php

namespace App\Infrastructure\DomainEvent;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(DomainEventSubscriber::class)
        ->tag('doctrine.event_subscriber', ['connection' => 'default']);
};