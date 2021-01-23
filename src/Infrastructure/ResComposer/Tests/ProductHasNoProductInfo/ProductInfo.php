<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\ProductHasNoProductInfo;

use App\Infrastructure\ResComposer\Resource;

final class ProductInfo implements Resource
{
    public $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function promises(): array
    {
        return [];
    }
}
