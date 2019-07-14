<?php
declare(strict_types=1);

namespace App\Product\Model;

use App\Order\Model\ProductId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

final class Products extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findById(ProductId $productId): ?ProductId
    {
        $query = $this->_em->createQuery('
            select
                p
            from
                App\Event\Model\Product as p
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
