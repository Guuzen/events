<?php

declare(strict_types=1);

namespace App\Promocode\ClientApi\UsePromocode;

use App\Event\Model\EventId;
use App\Infrastructure\Http\AppController\AppController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class UsePromocodeHttpAdapter extends AppController
{
    private $handler;

    public function __construct(UsePromocodeHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @Route("/promocode/use", methods={"POST"})
     */
    public function __invoke(UsePromocodeRequest $request, EventId $eventId): Response
    {
        $usePromocode = $request->toUsePromocode($eventId);
        $this->handler->handle($usePromocode);

        $this->flush();

        return $this->response([]);
    }
}
