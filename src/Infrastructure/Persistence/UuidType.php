<?php

namespace App\Infrastructure\Persistence;

use App\Infrastructure\Uuid;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use ReflectionClass;
use Throwable;

// TODO make final
class UuidType extends Type
{
    /** @var class-string */
    private $name;

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @psalm-param class-string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    final public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        if (!is_string($value)) {
            throw ConversionException::conversionFailedInvalidType($value, $this->name, ['string']);
        }

        try {
            $reflectionClass    = new ReflectionClass($this->name);
            $baseUuidClass      = $reflectionClass->getParentClass();
            $reflectionProperty = $baseUuidClass->getProperty('id');
            $reflectionProperty->setAccessible(true);

            $uuid = $reflectionClass->newInstanceWithoutConstructor();
            $reflectionProperty->setValue($uuid, $value);
        } catch (Throwable $throwable) {
            throw ConversionException::conversionFailed($value, $this->name);
        }

        return $uuid;
    }

    final public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof Uuid) {
            return (string)$value;
        }

        if (null === $value) {
            return null;
        }

        throw ConversionException::conversionFailed((string)$value, $this->name);
    }

    final public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL($fieldDeclaration);
    }
}
