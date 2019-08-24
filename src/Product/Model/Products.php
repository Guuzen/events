<?php

namespace App\Product\Model;

use App\Common\Result\Ok;
use App\Common\Result\Result;
use App\Product\Model\Error\NotReservedProductNotFound;
use App\Product\Model\Error\ProductNotFound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

final class Products extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findNotReservedByType(ProductType $type): Result
    {
        $query = $this->_em->createQuery('
            select
                product
            from
                App\Product\Model\Product as product
            where
                JSON_GET_TEXT(product.type, \'type\') = :type
                and
                product.reserved = false
        ');
        $query->setParameter('type', $type);

        $product = $query->getOneOrNullResult();
        if (null === $product) {
            return new NotReservedProductNotFound();
        }

        return new Ok($product);
    }

    public function findById(ProductId $productId): Result
    {
        $query = $this->_em->createQuery('
            select
                product
            from
                App\Product\Model\Product as product
            where
                product.id = :product_id
        ');
        $query->setParameter('product_id', $productId);

        $product = $query->getOneOrNullResult();
        if (null === $product) {
            return new ProductNotFound();
        }

        return new Ok($product);
    }

    public function add(Product $product): void
    {
        $this->_em->persist($product);
    }
}
