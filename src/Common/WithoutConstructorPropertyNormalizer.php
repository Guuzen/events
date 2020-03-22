<?php

namespace App\Common;

use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;

final class WithoutConstructorPropertyNormalizer extends PropertyNormalizer
{
    /**
     * @var array
     */
    private $discriminatorCache = [];

    /**
     * @param string     $class
     * @param array|bool $allowedAttributes
     */
    protected function instantiateObject(
        array &$data,
        $class,
        array &$context,
        \ReflectionClass $reflectionClass,
        $allowedAttributes,
        string $format = null
    ): object
    {
        return $reflectionClass->newInstanceWithoutConstructor();
    }

    /**
     * @param object      $object
     * @param string      $attribute
     * @param string|null $format
     *
     * @return mixed
     */
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

        if (null !== $this->classDiscriminatorResolver && $attribute === $this->discriminatorCache[$cacheKey]) {
            return $this->classDiscriminatorResolver->getTypeForMappedObject($object);
        }

        return parent::getAttributeValue($object, $attribute, $format, $context);
    }
}
