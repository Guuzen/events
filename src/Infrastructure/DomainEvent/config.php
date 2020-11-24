<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Infrastructure\DomainEvent\DoctrineNotificationSubscriber;
use Symfony\Component\EventDispatcher\EventDispatcher;

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