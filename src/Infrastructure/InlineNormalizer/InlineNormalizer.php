<?php

declare(strict_types=1);

namespace App\Infrastructure\InlineNormalizer;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class InlineNormalizer implements DenormalizerInterface, NormalizerInterface
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var ClassMetadataFactoryInterface
     */
    private $classMetadataFactory;

    /**
     * @var DenormalizerInterface
     */
    private $denormalizer;

    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    public function __construct(Reader $reader, ClassMetadataFactoryInterface $classMetadataFactory)
    {
        $this->reader               = $reader;
        $this->classMetadataFactory = $classMetadataFactory;
    }

    /**
     * @psalm-suppress MixedInferredReturnType
     * @psalm-suppress MixedArgument
     * @psalm-suppress MixedReturnStatement
     */
    public function normalize($object, $format = null, array $context = [])
    {
        /** @psalm-suppress PossiblyNullReference */
        $typeMetadata = $this->classMetadataFactory->getMetadataFor($object);
        /** @psalm-suppress InternalMethod */
        $reflectionClass = $typeMetadata->getReflectionClass();
        $properties      = $reflectionClass->getProperties();
        if (\count($properties) > 1) {
            throw new UnexpectedValueException(
                \sprintf('It is not possible to inline more than one value for class: %s.', \get_class($object))
            );
        }
        $firstProperty = $properties[0];
        $firstProperty->setAccessible(true);

        return $this->normalizer->normalize($firstProperty->getValue($object));
    }

    public function supportsNormalization($data, $format = null): bool
    {
        if (!\is_object($data)) {
            return false;
        }

        /** @psalm-suppress PossiblyNullReference */
        $typeMetadata = $this->classMetadataFactory->getMetadataFor($data);
        /** @psalm-suppress InternalMethod */
        $reflectionClass = $typeMetadata->getReflectionClass();

        /** @var Inline|null $annotation */
        $annotation = $this->reader->getClassAnnotation($reflectionClass, Inline::class);

        return $annotation !== null;
    }

    /**
     * @psalm-suppress MixedInferredReturnType
     * @psalm-suppress MixedArgument
     * @psalm-suppress MixedReturnStatement
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        /** @psalm-suppress PossiblyNullReference */
        $typeMetadata = $this->classMetadataFactory->getMetadataFor($type);
        /** @psalm-suppress InternalMethod */
        $reflectionClass = $typeMetadata->getReflectionClass();
        $properties      = $reflectionClass->getProperties();

        if (\count($properties) > 1) {
            throw new UnexpectedValueException(
                \sprintf('It is not possible to inline objects with more than one property. Type: %s.', $type)
            );
        }

        $firstProperty = $properties[0];
        if ($firstProperty->isPublic() === false) {
            $firstProperty->setAccessible(true);
        }

        return $this->denormalizer->denormalize([$firstProperty->getName() => $data], $type, $format, $context);
    }

    public function supportsDenormalization($data, $type, $format = null, array $context = []): bool
    {
        /** @psalm-suppress PossiblyNullReference */
        $typeMetadata = $this->classMetadataFactory->getMetadataFor($type);
        /** @psalm-suppress InternalMethod */
        $reflectionClass = $typeMetadata->getReflectionClass();

        /** @var Inline|null $annotation */
        $annotation = $this->reader->getClassAnnotation($reflectionClass, Inline::class);

        return $annotation !== null;
    }

    public function setDenormalizer(DenormalizerInterface $denormalizer): void
    {
        $this->denormalizer = $denormalizer;
    }

    public function setNormalizer(NormalizerInterface $normalizer): void
    {
        $this->normalizer = $normalizer;
    }
}
