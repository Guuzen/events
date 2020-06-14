<?php

namespace App\Promocode\Action\CreateTariffPromocode;

use App\Infrastructure\Http\AppController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CreateTariffPromocodeHttpAdapter extends AppController
{
    private $handler;

    private $em;

    public function __construct(CreateTariffPromocodeHandler $handler, EntityManagerInterface $em)
    {
        $this->handler = $handler;
        $this->em      = $em;
    }

    /**
     * @Route("/admin/promocode/createTariff", methods={"POST"})
     */
    public function __invoke(CreateTariffPromocodeRequest $request): Response
    {
        $promocodeId = $this->handler->handle($request->toCreateTariffPromocode());

        $this->em->flush();

        return $this->response($promocodeId);
    }
}
