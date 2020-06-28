<?php

namespace App\Event\Action\CreateEvent;

use App\Infrastructure\Http\AppController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CreateEventHttpAdapter extends AppController
{
    private $em;

    private $handler;

    public function __construct(EntityManagerInterface $em, CreateEventHandler $handler)
    {
        $this->em      = $em;
        $this->handler = $handler;
    }

    /**
     * @Route("/admin/event/create", methods={"POST"})
     */
    public function __invoke(): Response
    {
        // TODO wrap transactions on kernel events ?
        $eventId = $this->handler->createEvent();

        $this->em->flush();

        // TODO how about response objects ?
        return $this->response($eventId);
    }
}
