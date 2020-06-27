<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\DoctrineTypesInitializer;

use App\Infrastructure\Persistence\UuidType;
use Doctrine\DBAL\Types\Type;

final class UuidTypes
{
    /** @var class-string[] */
    private $types;

    /**
     * @psalm-param class-string $type
     */
    public function addType(string $type): void
    {
        $this->types[$type] = $type;
    }

    public function initialize(): void
    {
        foreach ($this->types as $type) {
            if (!Type::hasType($type)) {
                Type::addType($type, UuidType::class);
                /** @var UuidType $uuidType */
                $uuidType = Type::getType($type);
                $uuidType->setName($type);
            }
        }
    }
}
