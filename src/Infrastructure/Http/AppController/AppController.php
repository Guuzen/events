<?php

namespace App\Infrastructure\Http\AppController;

use App\Common\Error;
use Psr\Container\ContainerInterface;
use ReflectionClass;
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
    protected function response($data): Response
    {
        return $data instanceof Error ? $this->errorJson($data) : $this->successJson($data);
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

    /**
     * @param mixed $data
     */
    private function successJson($data = [], int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        return $this->toJson([
            'data' => $data ?? [],
        ], $status, $headers, $context);
    }

    /**
     * @param Error|string $message
     */
    private function errorJson($message, int $status = 400, array $headers = [], array $context = []): JsonResponse
    {
        if ($message instanceof Error) {
            $message = $this->stringifyError($message);
        }

        return $this->toJson([
            'error' => $message,
        ], $status, $headers, $context);
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

    private function stringifyError(Error $errorObject): string
    {
        $errorClassName = (new ReflectionClass($errorObject))->getShortName();
        $message        = \preg_replace('/[A-Z]/', ' \\0', \lcfirst($errorClassName));
        if (null === $message) {
            throw new \RuntimeException();
        }

        return \strtolower($message);
    }
}
