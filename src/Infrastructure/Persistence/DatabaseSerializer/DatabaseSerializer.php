<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\DatabaseSerializer;

use Symfony\Component\Serializer\Serializer;

// TODO split serializer and deserializer ?
final class DatabaseSerializer
{
    private $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @template T
     *
     * @psalm-param mixed           $data
     * @psalm-param class-string<T> $type
     *
     * @psalm-return T
     *
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function deserialize($data, string $type, array $context = [])
    {
        /**
         * @var T $deserialized
         *
         * @psalm-suppress InvalidReturnStatement
         */
        $deserialized = $this->serializer->deserialize($data, $type, 'json', $context);

        return $deserialized;
    }

    /**
     * @psalm-param mixed $data
     */
    public function serialize($data, array $context = []): string
    {
        return $this->serializer->serialize($data, 'json', $context);
    }
}
