<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\User\JsonDocumentTypes;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\Mapping\Column;
use Doctrine\Persistence\Mapping\Driver\MappingDriver;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class AddJsonDocumentTypesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        /** @var Reader $annotationReader */
        $annotationReader = $container->get('annotation_reader');

        /** @var MappingDriver $mappingDriver */
        $mappingDriver   = $container->get('doctrine.orm.default_metadata_driver');

        $jsonDocumentTypes = $container->getDefinition(JsonDocumentTypes::class);
        $jsonDocumentTypes->setPublic(true);

        /** @psalm-var class-string[] $entityClasses */
        $entityClasses = $mappingDriver->getAllClassNames();
        foreach ($entityClasses as $entityClass) {
            $reflectionEntityClass = new \ReflectionClass($entityClass);
            $properties            = $reflectionEntityClass->getProperties();
            foreach ($properties as $property) {
                /** @var Column $column */
                $column = $annotationReader->getPropertyAnnotation($property, Column::class);
                if (\is_string($column->type) && !\class_exists($column->type) && !\interface_exists($column->type)) {
                    continue;
//                    throw new \RuntimeException(
//                        \sprintf('Class or interface "%s" does not exist', $column->type)
//                    );
                }
                $jsonDocumentTypes->addMethodCall('addType', [$column->type]);
            }
        }
    }
}
