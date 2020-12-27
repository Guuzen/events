<?php

declare(strict_types=1);

namespace App\Infrastructure\InlineNormalizer\Tests\DenormalizeTest;

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
use App\Infrastructure\InlineNormalizer\Tests\NormalizeTest\WithTwoProperties;

final class DenormalizeTest extends TestCase
{
    /**
     * @var InlineNormalizer
     */
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
        $data = null;

        $object = $this->normalizer->denormalize($data, Denormalizable::class);

        self::assertEquals(new Denormalizable(null), $object);
    }

    public function testInlineScalar(): void
    {
        $data = 'some string';

        $object = $this->normalizer->denormalize($data, Denormalizable::class);

        self::assertEquals(new Denormalizable('some string'), $object);
    }

    public function testInlineArray(): void
    {
        $data = [1];

        $object = $this->normalizer->denormalize($data, Denormalizable::class);

        self::assertEquals(new Denormalizable([1]), $object);
    }

    public function testInlineDenormalizableCollectionOfDenormalizables(): void
    {
        $data = [
            'foo',
            'bar',
        ];

        $object = $this->normalizer->denormalize($data, DenormalizableCollectionOfDenormalizables::class);

        self::assertEquals(
            new DenormalizableCollectionOfDenormalizables(
                [
                    new Denormalizable('foo'),
                    new Denormalizable('bar'),
                ]
            ),
            $object
        );
    }

    public function testItIsNotPossibleToInlineManyProperties(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $nonsenseData = [];

        $this->normalizer->denormalize($nonsenseData, WithTwoProperties::class);
    }
}
