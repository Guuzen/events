<?php

declare(strict_types=1);

namespace App\TariffDescription;

use App\Infrastructure\Http\AppController;
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
     * @Route("/admin/tariffDescription/show", methods={"GET"})
     */
    public function show(FindTariffDescriptionByIdRequest $request): Response
    {
        $tariffDescription = $this->tariffDescriptions->find(new TariffDescriptionId($request->tariffId));

        return $this->response($tariffDescription);
    }
}
