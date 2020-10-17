<?php

namespace App\Infrastructure\Persistence\DBALTypes;

use App\Infrastructure\Persistence\DatabaseSerializer\DatabaseSerializer;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class JsonDocumentType extends Type implements CustomType
{
    /** @var DatabaseSerializer */
    private $serializer;

    /** @psalm-var class-string */
    private $mappedClass;

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return $this->serializer->deserialize($value, $this->mappedClass);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return $this->serializer->serialize($value);
    }

    public function setMappedClass(string $mappedClass): void
    {
        $this->mappedClass = $mappedClass;
    }

    public function getName(): string
    {
        return $this->mappedClass;
    }

    public function setSerializer(DatabaseSerializer $serializer): void
    {
        $this->serializer = $serializer;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getJsonTypeDeclarationSQL($fieldDeclaration);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
