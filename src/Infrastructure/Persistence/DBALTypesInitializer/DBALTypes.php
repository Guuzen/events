<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\DBALTypesInitializer;

use Doctrine\DBAL\Types\Type;

final class DBALTypes
{
    /** @var array<class-string, class-string<Type>> */
    private $types;

    /**
     * @param class-string       $mappedClass
     * @param class-string<Type> $dbalTypeClass
     */
    public function addType(string $mappedClass, string $dbalTypeClass): void
    {
        $this->types[$mappedClass] = $dbalTypeClass;
    }

    public function initialize(): void
    {
        foreach ($this->types as $mappedClass => $doctrineTypeClass) {
            if (!Type::hasType($mappedClass)) {
                Type::addType($mappedClass, $doctrineTypeClass);

                /** @var CustomType $dbalType */
                $dbalType = Type::getType($mappedClass);
                $dbalType->setMappedClass($mappedClass);
            }
        }
    }
}
