<?php



namespace App\Common;

use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;

final class WithoutConstructorPropertyNormalizer extends PropertyNormalizer
{
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
}
