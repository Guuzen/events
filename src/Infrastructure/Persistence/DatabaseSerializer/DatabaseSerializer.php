<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\DatabaseSerializer;

use Symfony\Component\Serializer\SerializerInterface;

// TODO split serializer and deserializer ?
final class DatabaseSerializer
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @template T
     *
     * @psalm-param mixed $data
     * @psalm-param class-string<T> $type
     *
     * @psalm-return T
     *
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function deserialize($data, string $type, array $context = [])
    {
        /** @psalm-suppress InvalidReturnStatement */
        return $this->serializer->deserialize($data, $type, 'json', $context);
    }

    /**
     * @template T
     *
     * @psalm-param class-string<T> $type
     *
     * @psalm-return array<array-key, T>
     *
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress MixedReturnTypeCoercion
     */
    public function deserializeToArray(string $data, string $type, array $context = []): array
    {
        /** @psalm-suppress InvalidReturnStatement */
        return $this->serializer->deserialize($data, $type . '[]', 'json', $context);
    }

    /**
     * @psalm-param mixed $data
     */
    public function serialize($data, array $context = []): string
    {
        return $this->serializer->serialize($data, 'json', $context);
    }
}
