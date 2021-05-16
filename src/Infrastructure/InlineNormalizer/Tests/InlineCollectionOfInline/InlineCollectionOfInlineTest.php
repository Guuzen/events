<?php

declare(strict_types=1);

namespace App\Infrastructure\InlineNormalizer\Tests\InlineCollectionOfInline;

use App\Infrastructure\InlineNormalizer\Tests\InlineNormalizerTestCase;

final class InlineCollectionOfInlineTest extends InlineNormalizerTestCase
{
    public function testNormalize(): void
    {
        $object = new InlineCollectionOfInline(
            [
                new Inline('foo'),
                new Inline('bar'),
            ]
        );

        self::assertTrue($this->normalizer->supportsNormalization($object));

        $data = $this->normalizer->normalize($object);

        self::assertEquals(['foo', 'bar'], $data);
    }

    public function testDenormalize(): void
    {
        $data = [
            'foo',
            'bar',
        ];

        self::assertTrue(
            $this->normalizer->supportsDenormalization($data, InlineCollectionOfInline::class)
        );

        /** @var InlineCollectionOfInline $object */
        $object = $this->normalizer->denormalize($data, InlineCollectionOfInline::class);

        self::assertEquals(
            new InlineCollectionOfInline(
                [
                    new Inline('foo'),
                    new Inline('bar'),
                ]
            ),
            $object
        );
    }
}
