<?php

namespace App\Tariff\Action;

use App\Infrastructure\Http\AppController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class TariffHttpAdapter extends AppController
{
    private $handler;

    public function __construct(TariffHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @Route("/admin/tariff/create")
     */
    public function create(CreateTariff $createTariff): Response
    {
        $tariffId = $this->handler->createTariff($createTariff);

        return $this->response($tariffId);
    }
}
