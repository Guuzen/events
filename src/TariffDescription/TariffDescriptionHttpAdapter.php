<?php

declare(strict_types=1);

namespace App\TariffDescription;

use App\Infrastructure\Http\AppController\AppController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class TariffDescriptionHttpAdapter extends AppController
{
    private $tariffDescriptions;

    public function __construct(TariffDescriptions $tariffDescriptions)
    {
        $this->tariffDescriptions = $tariffDescriptions;
    }

    /**
     * @Route("/admin/tariffDescription/{tariffId}", methods={"GET"})
     */
    public function show(FindTariffDescriptionByIdRequest $request): Response
    {
        $tariffDescription = $this->tariffDescriptions->find($request->tariffId);

        return $this->response($tariffDescription);
    }

    /**
     * @Route("/admin/tariffDescription", methods={"POST"})
     */
    public function create(TariffDescription $tariffDescription): Response
    {
        $this->tariffDescriptions->add($tariffDescription);

        $this->flush();

        return $this->response([]);
    }
}
