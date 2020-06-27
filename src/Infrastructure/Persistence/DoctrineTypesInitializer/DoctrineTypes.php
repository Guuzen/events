<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\DoctrineTypesInitializer;

use Doctrine\DBAL\Types\Type;

final class DoctrineTypes
{
    /** @psalm-var array<class-string, class-string> */
    private $types;

    /**
     * @psalm-param class-string $mappedClass
     * @psalm-param class-string $doctrineTypeClass
     */
    public function addType(string $mappedClass, string $doctrineTypeClass): void
    {
        $this->types[$mappedClass] = $doctrineTypeClass;
    }

    public function initialize(): void
    {
        foreach ($this->types as $mappedClass => $doctrineTypeClass) {
            if (!Type::hasType($mappedClass)) {
                Type::addType($mappedClass, $doctrineTypeClass);

                /** @var CustomDoctrineType $doctrineType */
                $doctrineType = Type::getType($mappedClass);
                $doctrineType->setMappedClass($mappedClass);
            }
        }
    }
}
