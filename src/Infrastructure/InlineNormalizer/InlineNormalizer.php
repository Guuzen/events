<?php

declare(strict_types=1);

namespace App\Infrastructure\InlineNormalizer;

use App\Infrastructure\WithoutConstructorPropertyNormalizer;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorResolverInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

final class InlineNormalizer extends WithoutConstructorPropertyNormalizer
{
    /**
     * @var Reader
     */
    private $reader;

    public function __construct(
        Reader $reader,
        ClassMetadataFactoryInterface $classMetadataFactory = null,
        NameConverterInterface $nameConverter = null,
        PropertyTypeExtractorInterface $propertyTypeExtractor = null,
        ClassDiscriminatorResolverInterface $classDiscriminatorResolver = null,
        callable $objectClassResolver = null,
        array $defaultContext = []
    )
    {
        $this->reader = $reader;
        parent::__construct(
            $classMetadataFactory,
            $nameConverter,
            $propertyTypeExtractor,
            $classDiscriminatorResolver,
            $objectClassResolver,
            $defaultContext
        );
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

        /** @psalm-suppress UndefinedInterfaceMethod */
        return $this->serializer->normalize($firstProperty->getValue($object));
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

        /** @var InlineDenormalizable|null $annotation */
        $annotation = $this->reader->getClassAnnotation($reflectionClass, InlineNormalizable::class);

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

        return parent::denormalize([$firstProperty->getName() => $data], $type, $format, $context);
    }

    public function supportsDenormalization($data, $type, $format = null): bool
    {
        /** @psalm-suppress PossiblyNullReference */
        $typeMetadata = $this->classMetadataFactory->getMetadataFor($type);
        /** @psalm-suppress InternalMethod */
        $reflectionClass = $typeMetadata->getReflectionClass();

        /** @var InlineDenormalizable|null $annotation */
        $annotation = $this->reader->getClassAnnotation($reflectionClass, InlineDenormalizable::class);

        return $annotation !== null;
    }
}
