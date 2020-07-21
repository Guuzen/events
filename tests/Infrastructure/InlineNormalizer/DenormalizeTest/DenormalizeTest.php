<?php

declare(strict_types=1);

namespace Tests\Infrastructure\InlineNormalizer\DenormalizeTest;

use App\Infrastructure\InlineNormalizer\InlineNormalizer;
use Doctrine\Common\Annotations\Reader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;

final class DenormalizeTest extends TestCase
{
    private $normalizer;

    protected function setUp(): void
    {
        $this->normalizer = new InlineNormalizer($this->createStub(Reader::class));
    }

    public function testInlineNull(): void
    {
        $data = [
            'foo' => null,
        ];

        $object = $this->normalizer->denormalize($data, Denormalizable::class);

        self::assertEquals(null, $object);
    }

    public function testInlineScalar(): void
    {
        $data = [
            'foo' => 'some string',
        ];

        $object = $this->normalizer->denormalize($data, Denormalizable::class);

        self::assertEquals('some string', $object);
    }

    public function testInlineArray(): void
    {
        $data = [
            'foo' => [1],
        ];

        $object = $this->normalizer->denormalize($data, Denormalizable::class);

        self::assertEquals([1], $object);
    }

    public function testItIsNotPossibleToInlineManyValues(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $data = [
            'foo' => '1231231',
            'bar' => 'sdfsdf',
        ];

        $this->normalizer->denormalize($data, Denormalizable::class);
    }
}
