<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\DatabaseSerializer;

use App\Infrastructure\ArrayKeysNameConverter\ArrayKeysNameConverter;
use Symfony\Component\Serializer\Serializer;

// TODO split serializer and deserializer ?
final class DatabaseSerializer
{
    private $serializer;

    private $arrayKeysNameConverter;

    public function __construct(Serializer $serializer, ArrayKeysNameConverter $arrayKeysNameConverter)
    {
        $this->serializer             = $serializer;
        $this->arrayKeysNameConverter = $arrayKeysNameConverter;
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
     * @psalm-suppress MixedReturnStatement
     * @psalm-suppress MixedInferredReturnType
     * @psalm-suppress MixedArgument
     * @psalm-suppress MixedAssignment
     */
    public function decode(string $json): array
    {
        $decoded = $this->serializer->decode($json, 'json');

        return $this->arrayKeysNameConverter->convert($decoded);
    }

    /**
     * @return mixed
     */
    public function denormalize(array $data, string $type)
    {
        return $this->serializer->denormalize($data, $type);
    }

    /**
     * @psalm-param mixed $data
     */
    public function serialize($data, array $context = []): string
    {
        return $this->serializer->serialize($data, 'json', $context);
    }
}
