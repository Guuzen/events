<?php

declare(strict_types=1);

namespace App\TariffDescription;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class TariffDescriptions extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TariffDescription::class);
    }

    public function add(TariffDescription $tariffDescription): void
    {
        $this->_em->persist($tariffDescription);
    }
}
