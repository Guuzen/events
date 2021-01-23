<?php

declare(strict_types=1);

namespace App\Promocode\Frontend\GetPromocodeList;

use App\Infrastructure\Http\AppController\AppController;
use App\Infrastructure\ResComposer\ResourceComposer;
use App\Promocode\Frontend\GetPromocodeList\GetPromocodeListHandler;
use App\Promocode\Frontend\Resource\PromocodeResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetPromocodeListHttpAdapter extends AppController
{
    private $handler;

    private $composer;

    public function __construct(GetPromocodeListHandler $handler, ResourceComposer $composer)
    {
        $this->handler  = $handler;
        $this->composer = $composer;
    }

    /**
     * @Route("/admin/promocode/list", methods={"GET"})
     */
    public function __invoke(GetPromocodeListRequest $request): Response
    {
        $promocodes = $this->handler->handle($request->eventId);

        $resources = $this->composer->compose($promocodes, PromocodeResource::class);

        return $this->response($resources);
    }
}
