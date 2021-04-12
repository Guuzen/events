<?php

namespace App\Infrastructure\ResComposer\Tests\ProductHasNoProductInfo;

use App\Infrastructure\ResComposer\Link\OneToOne;
use App\Infrastructure\ResComposer\Promise;
use App\Infrastructure\ResComposer\ResourceResolver;
use App\Infrastructure\ResComposer\Tests\StubResourceDataLoader;
use App\Infrastructure\ResComposer\Tests\TestCase;

final class ProductHasNoProductInfoTest extends TestCase
{
    public function test(): void
    {
        $productId   = 'nonsense';
        $product     = ['id' => $productId];
        $productInfo = ['id' => '10'];

        $this->composer->registerLoader(new StubResourceDataLoader([$productInfo]));
        $this->composer->registerResolver(
            new ResourceResolver(
                Product::class,
                new OneToOne('id'),
                ProductInfo::class,
                StubResourceDataLoader::class,
                fn (Product $product) => [
                    new Promise(
                        fn(Product $product) => $product->id,
                        fn(Product $product, ?ProductInfo $productInfo) => $product->productInfo = $productInfo,
                        $product,
                    )
                ],
            )
        );

        /** @var Product $resources */
        $resources = $this->composer->composeOne($product, Product::class);

        self::assertEquals(null, $resources->productInfo);
    }
}
