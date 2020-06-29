<?php

declare(strict_types=1);

namespace App\EventDomain\Action;

use App\EventDomain\Model\EventDomain;
use App\Infrastructure\Http\AppController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CreateEventDomainHttpAdapter extends AppController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/admin/eventDomain/create")
     */
    public function __invoke(EventDomain $eventDomain): Response
    {
        $this->em->persist($eventDomain);
        $this->em->flush();

        return $this->response([]);
    }
}
