<?php

declare(strict_types=1);

namespace App\Infrastructure\InlineNormalizer\Tests\ItIsNotPossibleToInlineManyValues;

use App\Infrastructure\InlineNormalizer\Tests\InlineNormalizerTestCase;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;

final class ItIsNotPossibleToInlineManyValuesTest extends InlineNormalizerTestCase
{
    public function testNormalize(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $object = new WithTwoProperties('1231231', 'sdfsdf');

        $this->normalizer->normalize($object);
    }

    public function testDenormalize(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $nonsenseData = [];

        $this->normalizer->denormalize($nonsenseData, WithTwoProperties::class);
    }
}
