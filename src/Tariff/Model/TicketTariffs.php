<?php
declare(strict_types=1);

namespace App\Tariff\Model;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

// TODO удалить зависимость от EntityRepository
final class TicketTariffs extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TicketTariff::class);
    }

    public function findById(TicketTariffId $ticketTariffId): ?TicketTariff
    {
        $query = $this->_em->createQuery('
            select
                t
            from
                App\Tariff\Model\TicketTariff as t
            where
                t.id = :id
        ');
        $query->setParameter('id', $ticketTariffId);

        return $query->getOneOrNullResult();
    }

    public function add(TicketTariff $tariff): void
    {
        $this->_em->persist($tariff);
    }
}
