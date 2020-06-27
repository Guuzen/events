<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Infrastructure\Persistence\DoctrineTypesInitializer\DoctrineTypes;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\Mapping\Column;
use Doctrine\Persistence\Mapping\Driver\MappingDriver;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Collect all custom DBAL types
 */
final class CollectDoctrineTypesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        /** @var Reader $annotationReader */
        $annotationReader = $container->get('annotation_reader');

        /** @var MappingDriver $mappingDriver */
        $mappingDriver   = $container->get('doctrine.orm.default_metadata_driver');

        $doctrineTypes = $container->getDefinition(DoctrineTypes::class);
        $doctrineTypes->setPublic(true);

        /** @psalm-var class-string[] $entityClasses */
        $entityClasses = $mappingDriver->getAllClassNames();
        foreach ($entityClasses as $entityClass) {
            $reflectionEntityClass = new \ReflectionClass($entityClass);
            $properties            = $reflectionEntityClass->getProperties();
            foreach ($properties as $property) {
                /** @var Column $column */
                $column = $annotationReader->getPropertyAnnotation($property, Column::class);

                /** @var string $columnType */
                $columnType = $column->type;
                if (!\class_exists($columnType) && !\interface_exists($columnType)) {
                    continue;
                }
                /** @psalm-var class-string */
                $doctrineTypeClass = $column->options['typeClass'];
                $doctrineTypes->addMethodCall('addType', [$columnType, $doctrineTypeClass]);
            }
        }
    }
}
