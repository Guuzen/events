<?php

declare(strict_types=1);

namespace Tests\Infrastructure\JoinResponse\Integrated;

use App\Infrastructure\ArrayComposer\Path\Path;
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
        $schema        = new Schema('productProvider');
        $schema->oneToOne(
            new Schema('productInfoProvider'),
            new Path(['productInfo']),
            new Path(['id']),
            'productInfo',
        );
        $providers = new ResourceProviders(
            [
                'productProvider'     => new StubResourceProvider($products),
                'productInfoProvider' => new StubResourceProvider([$productInfo]),
            ]
        );

        $result = $schema->collect($products, $providers);

        self::assertEquals(
            [
                ['id' => 'nonsense', 'productInfo' => null]
            ],
            $result
        );
    }
}
