<?php

namespace App\Common;

use DateTimeZone;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorFromClassMetadata;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class JsonDocumentType extends Type
{
    /** @var Serializer|null */
    private $serializer;

    // TODO psalm generics ?
    final public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $this->serializer) {
            $this->serializer = $this->createSerializer();
        }

        return $this->serializer->deserialize($value, $this->className(), 'json');
    }

    final public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $this->serializer) {
            $this->serializer = $this->createSerializer();
        }

        return $this->serializer->serialize($value, 'json');
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

    // TODO to container ?
    private function createSerializer(): Serializer
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $discriminator        = new ClassDiscriminatorFromClassMetadata($classMetadataFactory);

        return new Serializer([
            new DateTimeNormalizer([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s'], new DateTimeZone('UTC')),
            new ArrayDenormalizer(),
            new WithoutConstructorPropertyNormalizer(
                $classMetadataFactory,
                new CamelCaseToSnakeCaseNameConverter(),
                new PhpDocExtractor(),
                $discriminator
            ),
        ], [new JsonEncoder()]);
    }
}
