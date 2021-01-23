<?php

namespace App\Infrastructure\Http\AppController;

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
}
