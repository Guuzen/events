<?php

namespace App\Queries\Tariff;

use App\Infrastructure\Http\AppController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/tariff", methods={"GET"})
 */
final class TariffQueriesHttpAdapter extends AppController
{
    private $tariffQueries;

    public function __construct(TariffQueries $tariffQueries)
    {
        $this->tariffQueries = $tariffQueries;
    }

    /**
     * @Route("/list")
     */
    public function findEventTariffs(FindEventTariffs $findEventTariffs): Response
    {
        $tariffs = $this->tariffQueries->findEventTariffs($findEventTariffs->eventId);

        return $this->successJson($tariffs);
    }

    /**
     * @Route("/show")
     */
    public function findTariffById(FindTariffById $findTariffById): Response
    {
        $tariff = $this->tariffQueries->findById($findTariffById->tariffId);

        return $this->successJson($tariff);
    }
}
