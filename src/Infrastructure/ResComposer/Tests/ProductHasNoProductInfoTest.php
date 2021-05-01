<?php

namespace App\Infrastructure\ResComposer\Tests;

use App\Infrastructure\ResComposer\Config\MainResource;
use App\Infrastructure\ResComposer\Config\RelatedResource;
use App\Infrastructure\ResComposer\Link\OneToOne;
use App\Infrastructure\ResComposer\PromiseCollector\SimpleCollector;

final class ProductHasNoProductInfoTest extends TestCase
{
    public function test(): void
    {
        $productId   = 'nonsense';
        $product     = ['id' => $productId];
        $productInfo = ['id' => '10'];

        $this->composer->registerRelation(
            new MainResource('product', new SimpleCollector('id', 'productInfo')),
            new OneToOne(),
            new RelatedResource('productInfo', 'id', new StubResourceDataLoader([$productInfo])),
        );

        $resources = $this->composer->composeOne($product, 'product');

        self::assertEquals(['id' => $productId, 'productInfo' => null], $resources);
    }
}
