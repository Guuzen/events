<?php

declare(strict_types=1);

namespace App\Infrastructure\ResponseComposer;

final class ResourceProviders
{
    private $providers;

    /**
     * @param ResourceProvider[] $providers
     */
    public function __construct(array $providers)
    {
        $this->providers = $providers;
    }

    public function provider(string $providerId): ResourceProvider
    {
        return $this->providers[$providerId];
    }
}
