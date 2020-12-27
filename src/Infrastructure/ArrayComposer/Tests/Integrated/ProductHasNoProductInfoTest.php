<?php

declare(strict_types=1);

namespace App\Infrastructure\ArrayComposer\Tests\Integrated;

use App\Infrastructure\ArrayComposer\Path;
use App\Infrastructure\ArrayComposer\ResourceProviders;
use App\Infrastructure\ArrayComposer\Schema;
use PHPUnit\Framework\TestCase;

final class ProductHasNoProductInfoTest extends TestCase
{
    public function test(): void
    {
        $productInfoId = null;
        $product       = [
            'id' => 'nonsense',
        ];
        $products      = [
            $product,
        ];
        $productInfo   = ['id' => '10'];
        $schema        = new Schema();
        $schema->oneToOne(
            'productProvider',
            new Path(['productInfo']),
            'productInfoProvider',
            new Path(['id']),
            'productInfo',
        );
        $providers = new ResourceProviders(
            [
                'productProvider'     => new StubResourceProvider($products),
                'productInfoProvider' => new StubResourceProvider([$productInfo]),
            ]
        );

        $result = $schema->collect($products, 'productProvider', $providers);

        self::assertEquals(
            [
                ['id' => 'nonsense', 'productInfo' => null]
            ],
            $result
        );
    }
}
