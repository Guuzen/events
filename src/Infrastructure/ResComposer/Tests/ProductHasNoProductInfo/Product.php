<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\ProductHasNoProductInfo;

use App\Infrastructure\ResComposer\Promise;
use App\Infrastructure\ResComposer\Resource;
use App\Infrastructure\ResComposer\Tests\TestPromiseGroupResolver;

final class Product implements Resource
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var ProductInfo|null
     */
    public $productInfo;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function promises(): array
    {
        return [Promise::withProperties('id', 'productInfo', $this, TestPromiseGroupResolver::class)];
    }
}
