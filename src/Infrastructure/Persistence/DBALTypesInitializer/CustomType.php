<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\DBALTypesInitializer;

interface CustomType
{
    /**
     * @psalm-param class-string $mappedClass
     */
    public function setMappedClass(string $mappedClass): void;
}
