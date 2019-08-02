<?php

namespace App\Product\Model;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

final class Products extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findNotReservedByType(ProductType $type): ?Product
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

        return $query->getOneOrNullResult();
    }

    public function findById(ProductId $productId): ?Product
    {
        $query = $this->_em->createQuery('
            select
                p
            from
                App\Event\Model\ProductModel as p
            where
                p.id = :id
        ');
        $query->setParameter('id', $productId);

        return $query->getOneOrNullResult();
    }

    public function add(Product $product): void
    {
        $this->_em->persist($product);
    }
}
