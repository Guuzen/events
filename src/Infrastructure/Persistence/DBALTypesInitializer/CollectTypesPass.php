<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\DBALTypesInitializer;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\Mapping\Column;
use Doctrine\Persistence\Mapping\Driver\MappingDriver;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Collect all custom DBAL types
 */
final class CollectTypesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        /** @var Reader $annotationReader */
        $annotationReader = $container->get('annotation_reader');

        /** @var MappingDriver $mappingDriver */
        $mappingDriver = $container->get('doctrine.orm.default_metadata_driver');

        $doctrineTypes = $container->getDefinition(DBALTypes::class);
        $doctrineTypes->setPublic(true);

        /** @psalm-var class-string[] $entityClasses */
        $entityClasses = $mappingDriver->getAllClassNames();
        foreach ($entityClasses as $entityClass) {
            $reflectionEntityClass = new \ReflectionClass($entityClass);
            $properties            = $reflectionEntityClass->getProperties();
            foreach ($properties as $property) {
                /** @var Column $column */
                $column = $annotationReader->getPropertyAnnotation($property, Column::class);

                /** @var string $mappedClass */
                $mappedClass = $column->type;
                if (!\class_exists($mappedClass) && !\interface_exists($mappedClass)) {
                    continue;
                }

                $reflectionMappedClass = new \ReflectionClass($mappedClass);

                /** @var CustomTypeAnnotation|null $customDoctrineTypeClassAnnotation */
                $customDoctrineTypeClassAnnotation = $annotationReader->getClassAnnotation(
                    $reflectionMappedClass,
                    CustomTypeAnnotation::class
                );

                // An option to manually add types that has no annotations
                if ($customDoctrineTypeClassAnnotation === null) {
                    continue;
                }

                $doctrineTypes->addMethodCall('addType', [$mappedClass, $customDoctrineTypeClassAnnotation->typeClass]);
            }
        }
    }
}
