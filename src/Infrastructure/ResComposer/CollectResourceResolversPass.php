<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class CollectResourceResolversPass implements CompilerPassInterface
{
    public const RESOURCE_RESOLVER = 'resource_resolver';

    public function process(ContainerBuilder $container): void
    {
        $composer = $container->getDefinition(ResourceComposer::class);
        /** @var array<string, array> $taggedServices */
        $taggedServices = $container->findTaggedServiceIds(self::RESOURCE_RESOLVER);
        foreach ($taggedServices as $serviceId => $tags) {
            $composer->addMethodCall(
                'addResolver',
                [new Reference($serviceId)]
            );
        }
    }
}
