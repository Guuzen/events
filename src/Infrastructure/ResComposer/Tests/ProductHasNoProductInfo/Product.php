<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\ProductHasNoProductInfo;

use App\Infrastructure\ResComposer\Resource;

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

    public static function resolvers(): array
    {
        return [ProductHasProductInfo::class];
    }
}
