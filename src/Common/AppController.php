<?php

namespace App\Common;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AppController
{
    /**
     * @var ContainerInterface
     */
    private $locator;

    /**
     * @required
     */
    public function setLocator(ContainerInterface $httpAdapterLocator): void
    {
        $this->locator = $httpAdapterLocator;
    }

    protected function successJson($data = [], $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        return $this->json([
            'data' => $data,
        ], $status, $headers, $context);
    }

    protected function errorJson(string $message, $status = 400, array $headers = [], array $context = []): JsonResponse
    {
        return $this->json([
            'error' => $message,
        ], $status, $headers, $context);
    }

    private function json($data, int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        $json = $this->locator->get('serializer')->serialize($data, 'json', array_merge([
            'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
        ], $context));

        return new JsonResponse($json, $status, $headers, true);
    }
}
