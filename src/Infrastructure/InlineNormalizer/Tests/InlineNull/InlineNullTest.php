<?php

declare(strict_types=1);

namespace App\Infrastructure\InlineNormalizer\Tests\InlineNull;

use App\Infrastructure\InlineNormalizer\Tests\InlineNormalizerTestCase;

final class InlineNullTest extends InlineNormalizerTestCase
{
    public function testNormalize(): void
    {
        $object = new InlineNull(null);

        self::assertTrue($this->normalizer->supportsNormalization($object));

        $data = $this->normalizer->normalize($object);

        self::assertEquals(null, $data);
    }

    public function testDenormalize(): void
    {
        $data = null;

        self::assertTrue($this->normalizer->supportsDenormalization($data, InlineNull::class));

        /** @var InlineNull $object */
        $object = $this->normalizer->denormalize($data, InlineNull::class);

        self::assertEquals(new InlineNull(null), $object);
    }
}
