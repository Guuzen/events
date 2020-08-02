<?php

declare(strict_types=1);

namespace Tests\Infrastructure\InlineNormalizer\NormalizeTest;

use App\Infrastructure\InlineNormalizer\InlineNormalizer;
use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;

final class NormalizeTest extends TestCase
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
        $serializer       = new Serializer(
            [
                new ArrayDenormalizer(),
                $this->normalizer,
                new PropertyNormalizer()
            ]
        );

        $this->normalizer->setSerializer($serializer);
    }

    public function testInlineNull(): void
    {
        $object = new Normalizable(null);

        $data = $this->normalizer->normalize($object);

        self::assertEquals(null, $data);
    }

    public function testInlineScalar(): void
    {
        $object = new Normalizable('some string');

        $data = $this->normalizer->normalize($object);

        self::assertEquals('some string', $data);
    }

    public function testInlineArray(): void
    {
        $object = new Normalizable([1,2]);

        $data = $this->normalizer->normalize($object);

        self::assertEquals([1,2], $data);
    }

    public function testItIsNotPossibleToInlineManyValues(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $object = new WithTwoProperties('1231231', 'sdfsdf');

        $this->normalizer->normalize($object);
    }
}
