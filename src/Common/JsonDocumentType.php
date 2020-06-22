<?php

namespace App\Common;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class JsonDocumentType extends Type
{
    /** @var Serializer|null */
    private $serializer;

    /** @var string */
    protected $name;

    /** @psalm-var string|null */
    protected $className;

    // TODO psalm generics ?
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

    final public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    final public function setClassName(string $className): void
    {
        $this->className = $className;
    }

    final public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getJsonTypeDeclarationSQL($fieldDeclaration);
    }
}
