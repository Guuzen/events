<?php

namespace App\Infrastructure\Persistence\DBALTypes;

use App\Common\Serializer;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

// TODO make final
class JsonDocumentType extends Type implements CustomType
{
    /** @var Serializer|null */
    private $serializer;

    /** @var string */
    protected $name;

    /** @psalm-var string */
    protected $className;

    final public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $this->serializer) {
            $this->serializer = new Serializer();
        }

        if ($value === null) {
            return null;
        }

        /**
         * TODO
         *
         * @psalm-suppress MixedArgument
         * @psalm-suppress UndefinedMethod
         * @psalm-suppress DocblockTypeContradiction TODO remove when remove ??
         */
        return $this->serializer->deserialize($value, $this->className ?? $this->className());
    }

    final public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $this->serializer) {
            $this->serializer = new Serializer();
        }

        if ($value === null) {
            return null;
        }

        return $this->serializer->serialize($value);
    }

    final public function setMappedClass(string $mappedClass): void
    {
        $this->className = $mappedClass;
    }

    public function getName(): string
    {
        return $this->className;
    }

    final public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getJsonTypeDeclarationSQL($fieldDeclaration);
    }
}
