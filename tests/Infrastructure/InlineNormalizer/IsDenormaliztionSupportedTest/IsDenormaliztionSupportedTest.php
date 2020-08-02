<?php

declare(strict_types=1);

namespace Tests\Infrastructure\InlineNormalizer\IsDenormaliztionSupportedTest;

use App\Infrastructure\InlineNormalizer\InlineNormalizer;
use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;

final class IsDenormaliztionSupportedTest extends TestCase
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
        $nonsenseData = [];

        $isSupported = $this->normalizer->supportsDenormalization($nonsenseData, WithAnnotation::class);

        self::assertTrue($isSupported);
    }

    public function testItIsNotSupportedWithoutAnnotation(): void
    {
        $nonsenseData = [];

        $isSupported = $this->normalizer->supportsDenormalization($nonsenseData, WithoutAnnotation::class);

        self::assertFalse($isSupported);
    }
}
