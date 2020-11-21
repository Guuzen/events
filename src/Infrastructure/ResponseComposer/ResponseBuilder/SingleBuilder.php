<?php

declare(strict_types=1);

namespace App\Infrastructure\ResponseComposer\ResponseBuilder;

/**
 * @psalm-import-type Resource from \App\Infrastructure\ResponseComposer\Schema
 */
final class SingleBuilder implements ResponseBuilder
{
    private $class;

    /**
     * @psalm-var Resource
     */
    private $resource;

    private $builders;

    /**
     * @psalm-param class-string $class
     * @psalm-param Resource $resource
     * @psalm-param ResponseBuilder[] $builders
     */
    public function __construct(string $class, $resource, array $builders)
    {
        $this->class    = $class;
        $this->resource = $resource;
        $this->builders = $builders;
    }

    public function build(): object
    {
        $params = [];
        foreach ($this->builders as $builder) {
            $params[] = $builder->build();
        }

        /**
         * @psalm-suppress MixedMethodCall
         * @psalm-suppress UnsafeInstantiation
         */
        return new $this->class($this->resource, ...$params);
    }

    /**
     * @psalm-param callable(Resource): string $keyExtractor
     */
    public function extractKey(callable $keyExtractor): string
    {
        return $keyExtractor($this->resource);
    }
}
