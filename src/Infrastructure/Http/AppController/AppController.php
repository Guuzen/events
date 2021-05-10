<?php

namespace App\Infrastructure\Http\AppController;

use App\Infrastructure\ArrayKeysNameConverter\ArrayKeysNameConverter;
use App\Infrastructure\Http\Openapi\OpenapiValidator;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

abstract class AppController
{
    /**
     * @var ContainerInterface
     */
    private $locator;

    public function setLocator(ContainerInterface $locator): void
    {
        $this->locator = $locator;
    }

    protected function response(array $data, int $status = 200, array $headers = [], array $context = []): Response
    {
        /** @var RequestStack $requestStack */
        $requestStack = $this->locator->get('requestStack');
        /** @var OpenapiValidator $openapiValidator */
        $openapiValidator = $this->locator->get('openapiValidator');

        $request = $requestStack->getCurrentRequest();

        /** @var ArrayKeysNameConverter $keysNameConverter */
        $keysNameConverter = $this->locator->get('keysNameConverter');
        $data              = $keysNameConverter->convert($data);

        $response = $this->toJson(
            [
                'data' => $data,
            ], $status, $headers, $context
        );

        /** @psalm-suppress PossiblyNullArgument */
        $openapiValidator->validateResponse($request, $response);

        return $response;
    }

    private function toJson(array $data, int $status, array $headers = [], array $context = []): JsonResponse
    {
        return new JsonResponse($data, $status, $headers);
    }

    protected function persist(object $object): void
    {
        /** @var EntityManagerInterface $em */
        $em = $this->locator->get('em');

        $em->persist($object);
    }

    protected function flush(): void
    {
        /** @var EntityManagerInterface $em */
        $em = $this->locator->get('em');

        $em->flush();
    }
}
