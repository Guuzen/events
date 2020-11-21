<?php

declare(strict_types=1);

namespace Tests\Infrastructure\JoinResponse\Integrated;

use App\Infrastructure\ResponseComposer\ResourceProviders;
use App\Infrastructure\ResponseComposer\Schema;
use App\Infrastructure\ResponseComposer\SchemaProvider;
use PHPUnit\Framework\TestCase;

final class ProductHasNoProductInfoTest extends TestCase
{
    public function test(): void
    {
        $productInfoKey = null;
        $product        = [
            'id'            => 'nonsense',
            'productInfoId' => null,
        ];
        $products       = [
            $product,
        ];
        $productInfo    = ['id' => '10'];
        $schema         = Product::schema();
        $providers      = new ResourceProviders(
            [
                Product::class     => new StubResourceProvider($products),
                ProductInfo::class => new StubResourceProvider([$productInfo]),
            ]
        );

        $groupBuilder = $schema->collect($products, $providers);
        $responses    = $groupBuilder->build();

        self::assertEquals([new Product($product, null)], $responses);
    }
}

final class Product implements SchemaProvider
{
    private array $product;
    private ?ProductInfo $productInfo;

    public function __construct(array $product, ?ProductInfo $productInfo)
    {
        $this->product     = $product;
        $this->productInfo = $productInfo;
    }

    public static function schema(): Schema
    {
        $schema = new Schema(self::class);
        $schema->oneToOne(
            ProductInfo::schema(),
            fn(array $product) => (string)$product['productInfoId'],
            fn(array $productInfo) => (string)$productInfo['id'],
        );

        return $schema;
    }
}

final class ProductInfo implements SchemaProvider
{
    private array $productInfo;

    public function __construct(array $productInfo)
    {
        $this->productInfo = $productInfo;
    }

    public static function schema(): Schema
    {
        return new Schema(self::class);
    }
}
