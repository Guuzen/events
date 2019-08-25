<?php

namespace App\Product\Model;

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

    /**
     * @return Product|NotReservedProductNotFound
     */
    public function findNotReservedByType(ProductType $type)
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

        /** @var Product|null */
        $product = $query->getOneOrNullResult();
        if (null === $product) {
            return new NotReservedProductNotFound();
        }

        return $product;
    }

    /**
     * @return Product|ProductNotFound
     */
    public function findById(ProductId $productId)
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

        /** @var Product|null */
        $product = $query->getOneOrNullResult();
        if (null === $product) {
            return new ProductNotFound();
        }

        return $product;
    }

    public function add(Product $product): void
    {
        $this->_em->persist($product);
    }
}
