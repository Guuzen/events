<?php

namespace App\Common;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

abstract class JsonDocumentType extends Type
{
    /** @var Serializer|null */
    private $serializer;

    // TODO psalm generics ?
    final public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $this->serializer) {
            $this->serializer = new Serializer();
        }

        if ($value === null) {
            return null;
        }

        return $this->serializer->deserialize($value, $this->className());
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

    final public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getJsonTypeDeclarationSQL($fieldDeclaration);
    }

    final public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    abstract protected function className(): string;

    abstract public function getName(): string;
}
