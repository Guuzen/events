<?php

declare(strict_types=1);

namespace App\Common;

use DateTimeZone;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorFromClassMetadata;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

final class Serializer
{
    /** @var \Symfony\Component\Serializer\Serializer|null */
    private $serializer;

    /**
     * @param mixed $value
     *
     * @return bool|float|int|string
     */
    public function serialize($value)
    {
        if (null === $this->serializer) {
            $this->serializer = $this->createSerializer();
        }

        return $this->serializer->serialize($value, 'json');
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function deserialize($value, string $className)
    {
        if (null === $this->serializer) {
            $this->serializer = $this->createSerializer();
        }

        return $this->serializer->deserialize($value, $className, 'json');
    }

    private function createSerializer(): \Symfony\Component\Serializer\Serializer
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $discriminator        = new ClassDiscriminatorFromClassMetadata($classMetadataFactory);

        return new \Symfony\Component\Serializer\Serializer([
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
