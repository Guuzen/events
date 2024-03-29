<?php

namespace App\Infrastructure\Persistence;

use ReflectionClass;
use RuntimeException;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use function sprintf;

class PersistencePropertyNormalizer extends PropertyNormalizer
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
        ReflectionClass $reflectionClass,
        $allowedAttributes,
        string $format = null
    ): object
    {
        if ($this->classDiscriminatorResolver && $mapping = $this->classDiscriminatorResolver->getMappingForClass($class)) {
            if (!isset($data[$mapping->getTypeProperty()])) {
                throw new RuntimeException(sprintf('Type property "%s" not found for the abstract object "%s".', $mapping->getTypeProperty(), $class));
            }

            /** @var string $type */
            $type = $data[$mapping->getTypeProperty()];
            if (null === ($mappedClass = $mapping->getClassForType($type))) {
                throw new RuntimeException(sprintf('The type "%s" has no mapped class for the abstract object "%s".', $type, $class));
            }

            /** @psalm-var class-string $class */
            $class           = $mappedClass;
            $reflectionClass = new ReflectionClass($class);
        }

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

    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return class_exists($type) || (interface_exists($type, false) && $this->classDiscriminatorResolver && null !== $this->classDiscriminatorResolver->getMappingForClass($type));
    }
}
