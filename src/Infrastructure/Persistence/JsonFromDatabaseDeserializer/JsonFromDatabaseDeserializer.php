<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\JsonFromDatabaseDeserializer;

use App\Infrastructure\ArrayKeysNameConverter\ArrayKeysNameConverter;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

final class JsonFromDatabaseDeserializer
{
    /**
     * @var DecoderInterface
     */
    private $decoder;
    /**
     * @var ArrayKeysNameConverter
     */
    private $nameConverter;

    public function __construct(DecoderInterface $decoder, ArrayKeysNameConverter $nameConverter)
    {
        $this->decoder = $decoder;
        $this->nameConverter = $nameConverter;
    }

    public function deserialize(string $json, array $context = []): array
    {
        /** @var array $decoded */
        $decoded = $this->decoder->decode($json, 'json', $context);

        return $this->nameConverter->convert($decoded);
    }
}
