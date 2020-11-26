<?php

declare(strict_types=1);

namespace App\EventDomain\Action;

use App\EventDomain\Model\EventDomain;
use App\Infrastructure\Http\AppController\AppController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CreateEventDomainHttpAdapter extends AppController
{
    /**
     * TODO eventDomain cant be api request because of different requests for create and update
     *
     * @Route("/admin/eventDomain", methods={"POST"})
     */
    public function __invoke(EventDomain $eventDomain): Response
    {
        $this->persist($eventDomain);
        $this->flush();

        return $this->response([]);
    }
}
