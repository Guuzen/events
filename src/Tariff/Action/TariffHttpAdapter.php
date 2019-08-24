<?php

namespace App\Tariff\Action;

use App\Common\AppController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class TariffHttpAdapter extends AppController
{
    /**
     * @var TariffHandler
     */
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
        $result = $this->handler->createTariff($createTariff);

        if ($result->isErr()) {
            return $this->errorJson($result);
        }

        return $this->successJson($result->value());
    }
}
