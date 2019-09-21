<?php

namespace App\Infrastructure\DomainEvent;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class NotificationSubscriberPass implements CompilerPassInterface
{
    private const SERVICE_ID = 'app.infrastructure.domain_event.notification_dispatcher';

    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(self::SERVICE_ID)) {
            return;
        }
        $notificationDispatcher = $container->findDefinition(self::SERVICE_ID);

        /** @psalm-var array<class-string, array> */
        $taggedServices = $container->findTaggedServiceIds('app.notification_subscriber');
        foreach ($taggedServices as $id => $tags) {
            $notificationDispatcher->addMethodCall('addSubscriber', [new Reference($id)]);
        }
    }
}
