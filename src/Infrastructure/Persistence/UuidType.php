<?php

namespace App\Infrastructure\Persistence;

use App\Infrastructure\Uuid;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use ReflectionClass;
use Throwable;

/**
 * @template T of object
 */
abstract class UuidType extends Type
{
    abstract public function getName(): string;

    /**
     * @psalm-return class-string<T>
     */
    abstract protected function className(): string;

    /**
     * @psalm-return T|null
     */
    final public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        if (!is_string($value)) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['string']);
        }

        try {
            $reflectionClass    = new ReflectionClass($this->className());
            $baseUuidClass      = $reflectionClass->getParentClass();
            $reflectionProperty = $baseUuidClass->getProperty('id');
            $reflectionProperty->setAccessible(true);

            $uuid = $reflectionClass->newInstanceWithoutConstructor();
            $reflectionProperty->setValue($uuid, $value);
        } catch (Throwable $throwable) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        return $uuid;
    }

    final public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof Uuid) {
            return (string) $value;
        }

        if (null === $value) {
            return null;
        }

        throw ConversionException::conversionFailed((string) $value, $this->getName());
    }

    final public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL($fieldDeclaration);
    }

    final public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
