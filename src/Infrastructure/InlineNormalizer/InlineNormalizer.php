<?php

declare(strict_types=1);

namespace App\Infrastructure\InlineNormalizer;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class InlineNormalizer implements NormalizerInterface, DenormalizerInterface, SerializerAwareInterface
{
    private $reader;

    /**
     * @var NormalizerInterface|SerializerInterface
     */
    private $normalizer;

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @psalm-suppress MixedInferredReturnType
     * @psalm-suppress MixedArgument
     * @psalm-suppress MixedReturnStatement
     * @psalm-suppress PossiblyUndefinedMethod
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $reflectionClass = new \ReflectionClass($object);
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

        $reflectionClass = new \ReflectionClass($data);
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
        /** @psalm-suppress ArgumentTypeCoercion */
        $reflectionClass = new \ReflectionClass($type);
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

        $object = $reflectionClass->newInstanceWithoutConstructor();
        $firstProperty->setValue($object, $data);

        return $object;
    }

    public function supportsDenormalization($data, $type, $format = null): bool
    {
        /** @psalm-suppress ArgumentTypeCoercion */
        $reflectionClass = new \ReflectionClass($type);
        /** @var InlineDenormalizable|null $annotation */
        $annotation = $this->reader->getClassAnnotation($reflectionClass, InlineDenormalizable::class);

        return $annotation !== null;
    }


    public function setSerializer(SerializerInterface $serializer): void
    {
        $this->normalizer = $serializer;
    }
}
