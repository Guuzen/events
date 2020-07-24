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
}
