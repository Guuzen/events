<?php

declare(strict_types=1);

namespace Tests\Infrastructure\InlineNormalizer\NormalizeTest;

use App\Infrastructure\InlineNormalizer\InlineNormalizer;
use Doctrine\Common\Annotations\Reader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;

final class NormalizeTest extends TestCase
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
        $object = new Normalizable(null);

        $data = $this->normalizer->normalize($object);

        self::assertEquals($data, null);
    }

    public function testInlineScalar(): void
    {
        $object = new Normalizable('some string');

        $data = $this->normalizer->normalize($object);

        self::assertEquals($data, 'some string');
    }

    public function testInlineArray(): void
    {
        $object = new Normalizable([1,2]);

        $data = $this->normalizer->normalize($object);

        self::assertEquals($data, [1,2]);
    }

    public function testItIsNotPossibleToInlineManyValues(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $object = new WithTwoProperties('1231231', 'sdfsdf');

        $this->normalizer->normalize($object);
    }
}
