<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use App\Infrastructure\ResComposer\Resource;

final class ResourceDenormalizer
{
    private $promises;
    private $denormalizer;

    public function __construct(PromiseCollection $promises, DenormalizerInterface $denormalizer)
    {
        $this->promises     = $promises;
        $this->denormalizer = $denormalizer;
    }

    /**
     * @template T of Resource
     *
     * @param mixed           $data
     * @param class-string<T> $type
     *
     * @return array<int, T>
     */
    public function denormalize($data, string $type)
    {
        /**
         * @psalm-var array<int, T> $resources
         * @var Resource[]          $resources
         */
        $resources = $this->denormalizer->denormalize($data, $type . '[]');
        foreach ($resources as $resource) {
            $resolvers = $resource::resolvers();
            foreach ($resolvers as $resolver) {
                $promises = $resolver::collectPromises($resource);
                foreach ($promises as $promise) {
                    $this->promises->remember($promise, $resolver);
                }
            }
        }

        return $resources;
    }
}
