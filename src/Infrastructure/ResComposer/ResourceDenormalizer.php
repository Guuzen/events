<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use App\Infrastructure\ResComposer\Resource;
use App\Infrastructure\ResComposer\Link\Link;

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
     * @template T
     *
     * @param mixed                        $data
     * @param class-string<T>              $type
     * @param array<int, ResourceResolver> $resolvers
     *
     * @return array<int, T>
     */
    public function denormalize($data, string $type, array $resolvers)
    {
        /**
         * @psalm-var array<int, T> $resources
         * @var Resource[]          $resources
         */
        $resources = $this->denormalizer->denormalize($data, $type . '[]');
        foreach ($resources as $resource) {
            foreach ($resolvers as $resolverId => $resolver) {
                if ($resolver->resource === $type) {
                    $promises = ($resolver->resolver)($resource);
                    foreach ($promises as $promise) {
                        $this->promises->remember($promise, $resolverId);
                    }
                }
            }
        }

        return $resources;
    }
}
