<?php

namespace App\Infrastructure\ResComposer\Tests\ProductHasNoProductInfo;

use App\Infrastructure\ResComposer\Link\OneToOne;
use App\Infrastructure\ResComposer\Tests\StubResourceDataLoader;
use App\Infrastructure\ResComposer\Tests\TestCase;
use App\Infrastructure\ResComposer\Tests\TestPromiseGroupResolver;

final class ProductHasNoProductInfoTest extends TestCase
{
    public function test(): void
    {
        $productId   = 'nonsense';
        $product     = ['id' => $productId];
        $productInfo = ['id' => '10'];

        $this->composer->addResolver(
            new TestPromiseGroupResolver(
                new StubResourceDataLoader([$productInfo]),
                new OneToOne('id'),
                ProductInfo::class
            )
        );

        /** @var Product $resources */
        $resources = $this->composer->composeOne($product, Product::class);

        self::assertEquals(null, $resources->productInfo);
    }
}
