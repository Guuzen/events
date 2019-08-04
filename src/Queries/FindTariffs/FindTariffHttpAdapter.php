<?php

namespace App\Queries\FindTariffs;

use App\Common\AppController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/tariff/list", methods={"GET"})
 */
final class FindTariffHttpAdapter extends AppController
{
    private $findTariffs;

    public function __construct(FindTariffs $findTariffs)
    {
        $this->findTariffs = $findTariffs;
    }

    public function __invoke(Request $request): Response
    {
        $tariffs = ($this->findTariffs)();

        return $this->successJson($tariffs);
    }
}
