<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Common\JsonDocumentType;
use App\Infrastructure\Persistence\DoctrineTypesInitializer\JsonDocumentTypes;
use App\Infrastructure\Persistence\DoctrineTypesInitializer\UuidTypes;
use App\Infrastructure\Persistence\UuidType;
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

        $uuidTypes = $container->getDefinition(UuidTypes::class);
        $uuidTypes->setPublic(true);

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
//                    throw new \RuntimeException(
//                        \sprintf('Class or interface "%s" does not exist', $column->type)
//                    );
                }
                /** @psalm-var class-string */
                $typeClass = $column->options['typeClass'];
                if ($typeClass === UuidType::class) {
                    $uuidTypes->addMethodCall('addType', [$columnType]);
                } elseif ($typeClass === JsonDocumentType::class) {
                    $jsonDocumentTypes->addMethodCall('addType', [$columnType]);
                } else {
                    throw new \RuntimeException('Unknown type class ' . $typeClass . 'for type ' . $columnType);
                }
            }
        }
    }
}
