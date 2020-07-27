<?php

namespace App\Infrastructure\Persistence\DBALTypes;

use App\Infrastructure\Persistence\DBALTypesInitializer\CustomType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Serializer\SerializerInterface;

final class JsonDocumentType extends Type implements CustomType
{
    /** @var SerializerInterface|null */
    private $serializer;

    /** @var string */
    protected $name;

    /** @psalm-var string */
    protected $className;

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return $this->serializer->deserialize($value, $this->className, 'json');
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return $this->serializer->serialize($value, 'json');
    }

    public function setMappedClass(string $mappedClass): void
    {
        $this->className = $mappedClass;
    }

    public function getName(): string
    {
        return $this->className;
    }

    public function setSerializer(SerializerInterface $serializer): void
    {
        $this->serializer = $serializer;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getJsonTypeDeclarationSQL($fieldDeclaration);
    }
}
