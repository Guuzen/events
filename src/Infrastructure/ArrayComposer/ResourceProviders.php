<?php

declare(strict_types=1);

namespace App\Infrastructure\ArrayComposer;

class ResourceProviders
{
    private $providers;

    /**
     * @param ResourceProvider[] $providers
     */
    public function __construct(array $providers)
    {
        $this->providers = $providers;
    }

    /**
     * @param array-key[] $keys
     *
     * @return array<int, array>
     */
    public function provide(array $keys, string $providerId): array
    {
        return $this->providers[$providerId]->resources($keys);
    }
}
