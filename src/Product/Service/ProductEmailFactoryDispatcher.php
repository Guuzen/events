<?php

namespace App\Product\Service;

use App\Product\Model\ProductType;

final class ProductEmailFactoryDispatcher
{
    private $factoryMap;

    /**
     * @param ProductEmailFactory[] $factoryMap
     */
    public function __construct(array $factoryMap)
    {
        $this->factoryMap = $factoryMap;
    }

    public function dispatch(ProductType $productType): ProductEmailFactory
    {
        if (!isset($this->factoryMap[(string) $productType])) {
            throw new \Exception('unknown product type');
        }

        return $this->factoryMap[(string) $productType];
    }
}
