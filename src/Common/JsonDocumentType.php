<?php

namespace App\Common;

use DateTimeZone;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class JsonDocumentType extends Type
{
    /** @var Serializer $serializer */
    private $serializer;

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $this->initSerializer();

        return $this->serializer->deserialize($value, $this->className(), 'json');
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        $this->initSerializer();

        return $this->serializer->serialize($value, 'json');
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getJsonTypeDeclarationSQL($fieldDeclaration);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }

    abstract protected function className(): string;

    private function initSerializer(): void
    {
        if (null !== $this->serializer) {
            return;
        }

        $this->serializer = new Serializer([
            new DateTimeNormalizer([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s'], new DateTimeZone('UTC')),
            new ArrayDenormalizer(),
            new WithoutConstructorPropertyNormalizer(
                null,
                new CamelCaseToSnakeCaseNameConverter(),
                new PhpDocExtractor()
            ),
        ], [new JsonEncoder()]);
    }
}
