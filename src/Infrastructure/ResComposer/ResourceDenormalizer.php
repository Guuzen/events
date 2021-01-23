<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

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
            $promises = $resource->promises();
            /** @var Promise $promise */
            foreach ($promises as $promise) {
                $this->promises->remember($promise);
            }
        }

        return $resources;
    }
}
