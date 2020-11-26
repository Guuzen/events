<?php

namespace App\Tariff\Action;

use App\Infrastructure\Http\AppController\AppController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CreateTariffHttpAdapter extends AppController
{
    private $handler;

    public function __construct(CreateTariffHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @Route("/admin/tariff", methods={"POST"})
     */
    public function create(CreateTariffRequest $request): Response
    {
        $tariffId = $this->handler->createTariff($request->toCreateTariff());

        $this->flush();

        return $this->response($tariffId);
    }
}
