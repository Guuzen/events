<?php

declare(strict_types=1);

namespace Tests\Infrastructure\InlineNormalizer\DenormalizeTest;

use App\Infrastructure\InlineNormalizer\InlineNormalizer;
use Doctrine\Common\Annotations\Reader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;
use Tests\Infrastructure\InlineNormalizer\NormalizeTest\WithTwoProperties;

final class DenormalizeTest extends TestCase
{
    private $normalizer;

    protected function setUp(): void
    {
        $this->normalizer = new InlineNormalizer($this->createStub(Reader::class));
        $this->normalizer->setSerializer(new Serializer(
            [
                new PropertyNormalizer()
            ]
        ));
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

    public function testItIsNotPossibleToInlineManyValues(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $nonsenseData = [];

        $this->normalizer->denormalize($nonsenseData, WithTwoProperties::class);
    }
}
