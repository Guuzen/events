<?php

namespace App\Common;

use App\Common\Result\Err;
use App\Common\Result\Result;
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
        return $this->toJson([
            'data' => $data,
        ], $status, $headers, $context);
    }

    protected function errorJson($message, $status = 400, array $headers = [], array $context = []): JsonResponse
    {
        if ($message instanceof Err) {
            $message = $this->stringifyError($message);
        }

        return $this->toJson([
            'error' => $message,
        ], $status, $headers, $context);
    }

    private function toJson($data, int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        $json = $this->locator->get('serializer')->serialize($data, 'json', array_merge([
            'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
        ], $context));

        return new JsonResponse($json, $status, $headers, true);
    }

    private function stringifyError(Err $errorObject): string
    {
        $errorClassName = (new \ReflectionClass($errorObject))->getShortName();

        return strtolower(preg_replace('/[A-Z]/', ' \\0', lcfirst($errorClassName)));
    }
}
