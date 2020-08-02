<?php

declare(strict_types=1);

namespace Tests\Infrastructure\InlineNormalizer\IsNormaliztionSupportedTest;

use App\Infrastructure\InlineNormalizer\InlineNormalizer;
use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;

final class IsNormaliztionSupportedTest extends TestCase
{
    private $normalizer;

    protected function setUp(): void
    {
        $reader           = new AnnotationReader();
        $this->normalizer = new InlineNormalizer(
            $reader,
            new ClassMetadataFactory(new AnnotationLoader($reader)),
            null,
            new PhpDocExtractor()
        );
    }

    public function testItIsSupportedWithAnnotation(): void
    {
        $withAnnotation = new WithAnnotation();

        $isSupported = $this->normalizer->supportsNormalization($withAnnotation);

        self::assertTrue($isSupported);
    }

    public function testItIsNotSupportedWithoutAnnotation(): void
    {
        $withoutAnnotation = new WithoutAnnotation();

        $isSupported = $this->normalizer->supportsNormalization($withoutAnnotation);

        self::assertFalse($isSupported);
    }

    public function testItSupportsOnlyObjects(): void
    {
        $isSupported = $this->normalizer->supportsNormalization([1]);

        self::assertFalse($isSupported);
    }
}
