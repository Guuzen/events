<?php

namespace App\Infrastructure\Http\AppController;

use App\Infrastructure\ArrayComposer\ResourceProviders;
use App\Infrastructure\ArrayComposer\Schema;
use App\Infrastructure\Persistence\DatabaseSerializer\DatabaseSerializer;
use App\Infrastructure\Persistence\JsonFromDatabaseDeserializer\JsonFromDatabaseDeserializer;
use Doctrine\ORM\EntityManagerInterface;
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

    public function setLocator(ContainerInterface $locator): void
    {
        $this->locator = $locator;
    }

    /**
     * @param mixed $data
     */
    protected function response($data, int $status = 200, array $headers = [], array $context = []): Response
    {
        return $this->toJson(
            [
                'data' => $data ?? [],
            ], $status, $headers, $context
        );
    }

    protected function responseJoinedOne(array $resource, Schema $schema, string $resourceClass): Response
    {
        return $this->response($this->responseJoined([$resource], $schema, $resourceClass)[0]);
    }

    /**
     * @psalm-param array<int, array> $resources
     */
    protected function responseJoinedCollection(array $resources, Schema $schema, string $resourceClass): Response
    {

        return $this->response(
            $this->responseJoined($resources, $schema, $resourceClass)
        );
    }

    protected function deserializeFromDb(string $json, array $context = []): array
    {
        /** @var JsonFromDatabaseDeserializer $deserializer */
        $deserializer = $this->locator->get('jsonFromDatabaseConverter');

        return $deserializer->deserialize($json, $context);
    }

    public function persist(object $object): void
    {
        /** @var EntityManagerInterface $em */
        $em = $this->locator->get('em');

        $em->persist($object);
    }

    public function flush(): void
    {
        /** @var EntityManagerInterface $em */
        $em = $this->locator->get('em');

        $em->flush();
    }

    /**
     * @param mixed $data
     */
    private function toJson($data, int $status, array $headers = [], array $context = []): JsonResponse
    {
        /** @var SerializerInterface */
        $serializer = $this->locator->get('serializer');
        $json       = $serializer->serialize(
            $data, 'json', \array_merge(
                [
                    'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
                ], $context
            )
        );

        return new JsonResponse($json, $status, $headers, true);
    }

    /**
     * @psalm-suppress MixedReturnStatement
     * @psalm-suppress MixedInferredReturnType
     * @psalm-param array<int, array> $resources
     */
    private function responseJoined(array $resources, Schema $schema, string $resourceClass): array
    {
        /** @var ResourceProviders $resourceProviders */
        $resourceProviders = $this->locator->get('resourceProviders');

        /** @var DatabaseSerializer $serializer */
        $serializer = $this->locator->get('databaseSerializer');

        $collected = $schema->collect($resources, $resourceClass, $resourceProviders);

        return $serializer->denormalize($collected, $resourceClass . '[]');
    }
}
