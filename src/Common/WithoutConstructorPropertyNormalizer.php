<?php

namespace App\Common;

use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;

final class WithoutConstructorPropertyNormalizer extends PropertyNormalizer
{
    private $discriminatorCache = [];

    protected function instantiateObject(
        array &$data,
        $class,
        array &$context,
        \ReflectionClass $reflectionClass,
        $allowedAttributes,
        string $format = null
    ): object {
        return $reflectionClass->newInstanceWithoutConstructor();
    }

    protected function getAttributeValue($object, $attribute, $format = null, array $context = [])
    {
        $cacheKey = get_class($object);
        if (!array_key_exists($cacheKey, $this->discriminatorCache)) {
            $this->discriminatorCache[$cacheKey] = null;
            if (null !== $this->classDiscriminatorResolver) {
                $mapping                             = $this->classDiscriminatorResolver->getMappingForMappedObject($object);
                $this->discriminatorCache[$cacheKey] = null === $mapping ? null : $mapping->getTypeProperty();
            }
        }

        if ($attribute === $this->discriminatorCache[$cacheKey]) {
            return $this->classDiscriminatorResolver->getTypeForMappedObject($object);
        }

        return parent::getAttributeValue($object, $attribute, $format, $context);
    }
}
