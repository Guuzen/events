<?php

namespace App\Infrastructure\Persistence\DBALTypes;

use App\Infrastructure\Persistence\DBALTypesInitializer\CustomType;
use App\Infrastructure\Uuid;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use ReflectionClass;
use Throwable;

final class UuidType extends Type implements CustomType
{
    /** @var class-string */
    private $mappedClass;

    public function getName(): string
    {
        return $this->mappedClass;
    }

    public function setMappedClass(string $mappedClass): void
    {
        $this->mappedClass = $mappedClass;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        if (!\is_string($value)) {
            throw ConversionException::conversionFailedInvalidType($value, $this->mappedClass, ['string']);
        }

        try {
            $reflectionClass    = new ReflectionClass($this->mappedClass);
            $baseUuidClass      = $reflectionClass->getParentClass();
            $reflectionProperty = $baseUuidClass->getProperty('id');
            $reflectionProperty->setAccessible(true);

            $uuid = $reflectionClass->newInstanceWithoutConstructor();
            $reflectionProperty->setValue($uuid, $value);
        } catch (Throwable $throwable) {
            throw ConversionException::conversionFailed($value, $this->mappedClass);
        }

        return $uuid;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof Uuid) {
            return (string)$value;
        }

        if (null === $value) {
            return null;
        }

        throw ConversionException::conversionFailed((string)$value, $this->mappedClass);
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL($fieldDeclaration);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
