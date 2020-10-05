<?php

namespace App\Infrastructure\Http\AppController;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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

    /**
     * @param mixed $data
     *
     * @template T
     * @psalm-param class-string<T> $toClass
     *
     * @psalm-return T
     */
    protected function deserializeToViewModel($data, string $toClass)
    {
        /** @var SerializerInterface $deserializer */
        $deserializer = $this->locator->get('viewModelDeserializer');

        /** @psalm-var T $viewModel */
        $viewModel = $deserializer->deserialize($data, $toClass, 'json');

        return $viewModel;
    }

    protected function deserializeFromDb(string $json, array $context = []): array
    {
        /** @var SerializerInterface $serializer */
        $serializer = $this->locator->get('persistenceSerializer');

        return $serializer->deserialize($json, 'array', 'json', $context);
    }

    protected function toJsonResponse(string $json): Response
    {
        return new JsonResponse($json, 200, [], true);
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
