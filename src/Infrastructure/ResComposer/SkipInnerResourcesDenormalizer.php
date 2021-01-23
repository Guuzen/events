<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Prevent attempt to denormalize not loaded resource
 */
final class SkipInnerResourcesDenormalizer implements DenormalizerInterface
{
    /**
     * @psalm-suppress MixedInferredReturnType
     * @psalm-suppress MixedReturnStatement
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        return $data;
    }

    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return \is_subclass_of($type, Resource::class) && \is_array($data) === false;
    }
}
