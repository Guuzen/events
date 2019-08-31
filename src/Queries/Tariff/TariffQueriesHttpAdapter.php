<?php

namespace App\Queries\Tariff;

use App\Infrastructure\Http\AppController;
use App\Queries\Tariff\FindTariffById\FindTariffById;
use App\Queries\Tariff\FindTariffsInList\FindTariffsInList;
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
    public function findInList(FindTariffsInList $findTariffsInList): Response
    {
        $tariffs = $this->tariffQueries->findInList($findTariffsInList->eventId);

        return $this->successJson($tariffs);
    }

    /**
     * @Route("/show")
     */
    public function findById(FindTariffById $findTariffById): Response
    {
        $tariff = $this->tariffQueries->findById($findTariffById->tariffId);

        return $this->successJson($tariff);
    }
}
