<?php

namespace App\Infrastructure\ResComposer\Tests;

use App\Infrastructure\ResComposer\Link\OneToOne;
use App\Infrastructure\ResComposer\PromiseCollector\SimpleCollector;

final class ProductHasNoProductInfoTest extends TestCase
{
    public function test(): void
    {
        $productId   = 'nonsense';
        $product     = ['id' => $productId];
        $productInfo = ['id' => '10'];

        $this->composer->registerConfig(
            'product',
            new OneToOne('id'),
            'productInfo',
            new StubResourceDataLoader([$productInfo]),
            new SimpleCollector('id', 'productInfo'),
        );

        $resources = $this->composer->composeOne($product, 'product');

        self::assertEquals(['id' => $productId, 'productInfo' => null], $resources);
    }
}
