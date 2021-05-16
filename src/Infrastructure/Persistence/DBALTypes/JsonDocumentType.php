<?php

namespace App\Infrastructure\Persistence\DBALTypes;

use App\Infrastructure\InlineNormalizer\InlineNormalizer;
use App\Infrastructure\MoneyNormalizer;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomType;
use App\Infrastructure\WithoutConstructorPropertyNormalizer;
use DateTimeInterface;
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
use Symfony\Component\Serializer\SerializerInterface;

final class JsonDocumentType extends Type implements CustomType
{
    /** @psalm-var class-string */
    private $mappedClass;

    /**
     * @var SerializerInterface|null
     */
    private $serializer;

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        $serializer = $this->getSerializer();

        return $serializer->deserialize($value, $this->mappedClass, 'json');
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        $serializer = $this->getSerializer();

        return $serializer->serialize($value, 'json');
    }

    public function setMappedClass(string $mappedClass): void
    {
        $this->mappedClass = $mappedClass;
    }

    public function getName(): string
    {
        return $this->mappedClass;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getJsonTypeDeclarationSQL($fieldDeclaration);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    private function getSerializer(): SerializerInterface
    {
        if ($this->serializer !== null) {
            return $this->serializer;
        }

        $reader               = new AnnotationReader();
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader($reader));
        $inlineNormalizer     = new InlineNormalizer($reader, $classMetadataFactory);

        $propertyNormalizer = new WithoutConstructorPropertyNormalizer(
            $classMetadataFactory,
            new CamelCaseToSnakeCaseNameConverter(),
            new PhpDocExtractor(),
            new ClassDiscriminatorFromClassMetadata($classMetadataFactory),
        );

        $this->serializer = new Serializer(
            [
                new DateTimeNormalizer(
                    [
                        DateTimeNormalizer::FORMAT_KEY   => DateTimeInterface::ATOM,
                        DateTimeNormalizer::TIMEZONE_KEY => 'UTC',
                    ]
                ),
                new ArrayDenormalizer(),
                new MoneyNormalizer(),
                $inlineNormalizer,
                $propertyNormalizer,
            ],
            [new JsonEncoder()]
        );
        $inlineNormalizer->setNormalizer($this->serializer);
        $inlineNormalizer->setDenormalizer($propertyNormalizer);

        return $this->serializer;
    }
}
