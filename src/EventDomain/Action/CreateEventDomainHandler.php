<?php

declare(strict_types=1);

namespace App\EventDomain\Action;

use App\EventDomain\Model\EventDomain;
use Doctrine\ORM\EntityManagerInterface;

final class CreateEventDomainHandler
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function handle(EventDomain $eventDomain): void
    {
        $this->em->persist($eventDomain);
        $this->em->flush();
    }
}
