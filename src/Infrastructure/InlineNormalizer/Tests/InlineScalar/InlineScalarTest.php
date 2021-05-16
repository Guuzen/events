<?php

declare(strict_types=1);

namespace App\Infrastructure\InlineNormalizer\Tests\InlineScalar;

use App\Infrastructure\InlineNormalizer\Tests\InlineNormalizerTestCase;

final class InlineScalarTest extends InlineNormalizerTestCase
{
    public function testNormalize(): void
    {
        $object = new InlineScalar('some string');

        self::assertTrue($this->normalizer->supportsNormalization($object));

        $data = $this->normalizer->normalize($object);

        self::assertEquals('some string', $data);
    }

    public function testDenormalize(): void
    {
        $data = 'some string';

        self::assertTrue($this->normalizer->supportsDenormalization($data, InlineScalar::class));

        /** @var InlineScalar $object */
        $object = $this->normalizer->denormalize($data, InlineScalar::class);

        self::assertEquals(new InlineScalar('some string'), $object);
    }
}
