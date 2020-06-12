<?php

declare(strict_types=1);

namespace App\Promocode\Action\UsePromocode;

use App\Event\Model\EventId;
use App\Infrastructure\Http\AppController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class UsePromocodeHttpAdapter extends AppController
{
    private $handler;

    private $em;

    public function __construct(UsePromocodeHandler $handler, EntityManagerInterface $em)
    {
        $this->handler = $handler;
        $this->em      = $em;
    }

    /**
     * @Route("/promocode/use", methods={"POST"})
     */
    public function __invoke(UsePromocodeRequest $request, EventId $eventId): Response
    {
        $usePromocode = $request->toUsePromocode($eventId);
        $this->handler->handle($usePromocode);

        $this->em->flush();

        return $this->response([]);
    }
}
