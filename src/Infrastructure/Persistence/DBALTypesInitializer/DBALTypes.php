<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\DBALTypesInitializer;

use Doctrine\DBAL\Types\Type;

final class DBALTypes
{
    /** @psalm-var array<class-string, class-string> */
    private $types;

    /**
     * @psalm-param class-string $mappedClass
     * @psalm-param class-string $dbalTypeClass
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
