<?php

declare(strict_types=1);

namespace App\Promocode\AdminApi;

use App\Infrastructure\ResComposer\Link\OneToOne;
use App\Infrastructure\ResComposer\PromiseGroup;
use App\Infrastructure\ResComposer\PromiseGroupResolver;
use App\Promocode\AdminApi\Resource\PromocodeResource;

final class OrderHasOnePromocode implements PromiseGroupResolver
{
    private $loader;

    public function __construct(PromocodeLoader $loader)
    {
        $this->loader = $loader;
    }

    public function resolve(PromiseGroup $promises): void
    {
        $promises->resolve($this->loader, new OneToOne('id'), PromocodeResource::class);
    }
}
