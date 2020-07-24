<?php

declare(strict_types=1);

namespace Tests\Infrastructure\InlineNormalizer\IsDenormaliztionSupportedTest;

use App\Infrastructure\InlineNormalizer\InlineNormalizer;
use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;

final class IsDenormaliztionSupportedTest extends TestCase
{
    public function testItIsSupportedWithAnnotation(): void
    {
        $normalizer = new InlineNormalizer(new AnnotationReader());

        $nonsenseData = [];

        $isSupported = $normalizer->supportsDenormalization($nonsenseData, WithAnnotation::class);

        self::assertTrue($isSupported);
    }

    public function testItIsNotSupportedWithoutAnnotation(): void
    {
        $normalizer = new InlineNormalizer(new AnnotationReader());

        $nonsenseData = [];

        $isSupported = $normalizer->supportsDenormalization($nonsenseData, WithoutAnnotation::class);

        self::assertFalse($isSupported);
    }

    public function testStringsIsNotSupported(): void
    {
        $normalizer = new InlineNormalizer(new AnnotationReader());

        $nonsenseString = 'some string';

        $isSupported = $normalizer->supportsDenormalization($nonsenseString, WithAnnotation::class);

        self::assertFalse($isSupported);
    }

    public function testIntsIsNotSupported(): void
    {
        $normalizer = new InlineNormalizer(new AnnotationReader());

        $nonsenseInt = 1231;

        $isSupported = $normalizer->supportsDenormalization($nonsenseInt, WithAnnotation::class);

        self::assertFalse($isSupported);
    }

    public function testBoolsIsNotSupported(): void
    {
        $normalizer = new InlineNormalizer(new AnnotationReader());

        $nonsenseBool = true;

        $isSupported = $normalizer->supportsDenormalization($nonsenseBool, WithAnnotation::class);

        self::assertFalse($isSupported);
    }

    public function testFloatsIsNotSupported(): void
    {
        $normalizer = new InlineNormalizer(new AnnotationReader());

        $nonsenseFloat = 0.121;

        $isSupported = $normalizer->supportsDenormalization($nonsenseFloat, WithAnnotation::class);

        self::assertFalse($isSupported);
    }
}
