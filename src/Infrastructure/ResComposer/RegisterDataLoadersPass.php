<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterDataLoadersPass implements CompilerPassInterface
{
    public const DATA_LOADER = 'data_loader';

    public function process(ContainerBuilder $container): void
    {
        $composer = $container->getDefinition(ResourceComposer::class);
        /** @var array<string, array> $taggedServices */
        $taggedServices = $container->findTaggedServiceIds(self::DATA_LOADER);
        foreach ($taggedServices as $serviceId => $tags) {
            $composer->addMethodCall(
                'registerLoader',
                [new Reference($serviceId)]
            );
        }
    }
}
