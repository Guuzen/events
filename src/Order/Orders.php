<?php
declare(strict_types=1);

namespace App\Order;

use App\Order\Model\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;

final class Orders extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function getById(UuidInterface $invoiceId): Order
    {
        $query = $this->_em->createQuery('
            select
                i
            from
                App\Order\Invoice as i
            where
                i.id = :invoice_id
        ');
        $query->setParameter('invoice_id', $invoiceId);

        return $query->getSingleResult();
    }
}
