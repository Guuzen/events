<?php

declare(strict_types=1);

namespace App\Model\TariffDescription;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

final class TariffDescriptions extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TariffDescription::class);
    }

    public function getById(TariffDescriptionId $tariffDescriptionId): TariffDescription
    {
        $query = $this->createQueryBuilder('tariff_description')
            ->where('tariff_description.id = :tariff_description_id')
            ->setParameter('tariff_description_id', $tariffDescriptionId)
            ->getQuery();

        try {
            /** @var TariffDescription $tariffDescription */
            $tariffDescription = $query->getSingleResult();
        } catch (NoResultException $exception) {
            throw new TariffDescriptionNotFound('', 0, $exception);
        }

        return $tariffDescription;
    }

    public function add(TariffDescription $tariffDescription): void
    {
        $this->_em->persist($tariffDescription);
    }
}
