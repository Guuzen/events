<?php

declare(strict_types=1);

namespace Tests\Infrastructure\InlineNormalizer\IsNormaliztionSupportedTest;

use App\Infrastructure\InlineNormalizer\InlineNormalizer;
use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;

final class IsNormaliztionSupportedTest extends TestCase
{
    public function testItIsSupportedWithAnnotation(): void
    {
        $normalizer = new InlineNormalizer(new AnnotationReader());

        $withAnnotation = new WithAnnotation();

        $isSupported = $normalizer->supportsNormalization($withAnnotation);

        self::assertTrue($isSupported);
    }

    public function testItIsNotSupportedWithoutAnnotation(): void
    {
        $normalizer = new InlineNormalizer(new AnnotationReader());

        $withoutAnnotation = new WithoutAnnotation();

        $isSupported = $normalizer->supportsNormalization($withoutAnnotation);

        self::assertFalse($isSupported);
    }

    public function testItSupportsOnlyObjects(): void
    {
        $normalizer = new InlineNormalizer(new AnnotationReader());

        $isSupported = $normalizer->supportsNormalization([1]);

        self::assertFalse($isSupported);
    }
}
