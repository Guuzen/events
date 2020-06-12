<?php

namespace App\Tariff\Action;

use App\Infrastructure\Http\AppController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CreateTariffHttpAdapter extends AppController
{
    private $handler;

    private $em;

    public function __construct(CreateTariffHandler $handler, EntityManagerInterface $em)
    {
        $this->handler = $handler;
        $this->em      = $em;
    }

    /**
     * @Route("/admin/tariff/create")
     */
    public function create(CreateTariffRequest $request): Response
    {
        $tariffId = $this->handler->createTariff($request->toCreateTariff());

        $this->em->flush();

        return $this->response($tariffId);
    }
}
