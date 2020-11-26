<?php

namespace App\Infrastructure\DomainEvent;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\EventDispatcher\EventDispatcher;
use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(DoctrineNotificationSubscriber::class)
        ->args(
            [
                ref('app.infrastructure.domain_event.notification_dispatcher'),
            ]
        )
        ->tag('doctrine.event_subscriber', ['connection' => 'default']);

    $services->set('app.infrastructure.domain_event.notification_dispatcher', EventDispatcher::class);
};