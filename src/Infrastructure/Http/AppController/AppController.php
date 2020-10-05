<?php

namespace App\Infrastructure\Http\AppController;

use App\Infrastructure\ArrayKeysNameConverter\ArrayKeysNameConverter;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AppController
{
    /**
     * @var ContainerInterface
     */
    private $locator;

    /**
     * todo why do we need required ?
     *
     * @required
     */
    public function setLocator(ContainerInterface $locator): void
    {
        $this->locator = $locator;
    }

    /**
     * @param mixed $data
     */
    protected function response($data, int $status = 200, array $headers = [], array $context = []): Response
    {
        return $this->toJson([
            'data' => $data ?? [],
        ], $status, $headers, $context);
    }

    protected function deserializeFromDb(string $json, array $context = []): array
    {
        /** @var DecoderInterface $serializer */
        $serializer = $this->locator->get('persistenceSerializer');
        /** @var array $decoded */
        $decoded    = $serializer->decode($json, 'json', $context);

        /** @var ArrayKeysNameConverter $caseConverter */
        $caseConverter = $this->locator->get('arrayKeysNameConverter');

        return $caseConverter->convert($decoded);
    }

    /**
     * @param mixed $data
     */
    private function toJson($data, int $status, array $headers = [], array $context = []): JsonResponse
    {
        /** @var SerializerInterface */
        $serializer = $this->locator->get('serializer');
        $json       = $serializer->serialize($data, 'json', \array_merge([
            'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
        ], $context));

        return new JsonResponse($json, $status, $headers, true);
    }
}
