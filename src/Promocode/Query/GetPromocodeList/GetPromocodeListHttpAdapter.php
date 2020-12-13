<?php

declare(strict_types=1);

namespace App\Promocode\Query\GetPromocodeList;

use App\Infrastructure\Http\AppController\AppController;
use App\Promocode\Query\PromocodeResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetPromocodeListHttpAdapter extends AppController
{
    private $handler;

    public function __construct(GetPromocodeListHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @Route("/admin/promocode/list", methods={"GET"})
     */
    public function __invoke(GetPromocodeListRequest $request): Response
    {
        $promocodes = $this->handler->handle($request->eventId);

        return $this->responseJoinedCollection($promocodes, PromocodeResource::schema(), PromocodeResource::class);
    }
}