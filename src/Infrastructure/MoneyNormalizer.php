<?php

declare(strict_types=1);

namespace App\Infrastructure;

use Money\Currency;
use Money\Money;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class MoneyNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function normalize($object, $format = null, array $context = [])
    {
        /** @var Money $object */
        return $object->jsonSerialize();
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Money;
    }

    public function denormalize($data, $type, $format = null, array $context = [])
    {
        /**
         * @psalm-suppress MixedArrayAccess
         * @psalm-suppress MixedArgument
         */
        return new Money($data['amount'], new Currency($data['currency']));
    }

    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return Money::class === $type;
    }
}
