<?php

namespace App\Queries\Tariff;

use App\Common\AppController;
use Symfony\Component\HttpFoundation\Request;
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
    public function findAll(Request $request): Response
    {
        $tariffs = $this->tariffQueries->findAll();

        return $this->successJson($tariffs);
    }

    /**
     * @Route("/show")
     */
    public function findById(Request $request): Response
    {
        $tariffId = $request->get('tariff_id');
        $tariff   = $this->tariffQueries->findById($tariffId);

        return $this->successJson($tariff);
    }
}
