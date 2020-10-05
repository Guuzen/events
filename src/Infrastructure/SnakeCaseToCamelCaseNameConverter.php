<?php

declare(strict_types=1);

namespace App\Infrastructure;

use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

final class SnakeCaseToCamelCaseNameConverter implements NameConverterInterface
{
    /**
     * @var CamelCaseToSnakeCaseNameConverter
     */
    private $camelCaseToSnakeCaseNameConverter;

    /**
     * @var MetadataAwareNameConverter
     */
    private $metadataAwareNameConverter;

    public function __construct(
        CamelCaseToSnakeCaseNameConverter $camelCaseToSnakeCaseNameConverter
//        MetadataAwareNameConverter $metadataAwareNameConverter
    )
    {
        $this->camelCaseToSnakeCaseNameConverter = $camelCaseToSnakeCaseNameConverter;
//        $this->metadataAwareNameConverter        = $metadataAwareNameConverter;
    }

    public function normalize($propertyName): string
    {
        return $this->camelCaseToSnakeCaseNameConverter->denormalize($propertyName);
    }

    public function denormalize($propertyName): string
    {
        return $propertyName;
    }
}
