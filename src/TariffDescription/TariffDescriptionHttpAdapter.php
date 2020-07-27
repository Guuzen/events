<?php

declare(strict_types=1);

namespace App\TariffDescription;

use App\Infrastructure\Http\AppController\AppController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class TariffDescriptionHttpAdapter extends AppController
{
    private $tariffDescriptions;

    private $em;

    public function __construct(TariffDescriptions $tariffDescriptions, EntityManagerInterface $em)
    {
        $this->tariffDescriptions = $tariffDescriptions;
        $this->em                 = $em;
    }

    /**
     * @Route("/admin/tariffDescription/show", methods={"GET"})
     */
    public function show(FindTariffDescriptionByIdRequest $request): Response
    {
        $tariffDescription = $this->tariffDescriptions->find($request->tariffId);

        return $this->response($tariffDescription);
    }

    /**
     * @Route("/admin/tariffDescription/create", methods={"POST"})
     */
    public function create(TariffDescription $tariffDescription): Response
    {
        $this->tariffDescriptions->add($tariffDescription);

        $this->em->flush();

        return $this->response([]);
    }
}
