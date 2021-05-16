<?php

declare(strict_types=1);

namespace App\Infrastructure\InlineNormalizer\Tests\InlineArray;

use App\Infrastructure\InlineNormalizer\Tests\InlineNormalizerTestCase;

final class InlineArrayTest extends InlineNormalizerTestCase
{
    public function testNormalize(): void
    {
        $object = new InlineArray([1, 2]);

        self::assertTrue($this->normalizer->supportsNormalization($object));

        $data = $this->normalizer->normalize($object);

        self::assertEquals([1, 2], $data);
    }

    public function testDenormalize(): void
    {
        $data = [1, 2];

        self::assertTrue($this->normalizer->supportsDenormalization($data, InlineArray::class));

        /** @var InlineArray $object */
        $object = $this->normalizer->denormalize($data, InlineArray::class);

        self::assertEquals(new InlineArray([1, 2]), $object);
    }
}
