<?php

namespace App\Promocode\Frontend\CreateTariffPromocode;

use App\Infrastructure\Http\AppController\AppController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CreateTariffPromocodeHttpAdapter extends AppController
{
    private $handler;

    public function __construct(CreateTariffPromocodeHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @Route("/admin/promocode/createTariff", methods={"POST"})
     */
    public function __invoke(CreateTariffPromocodeRequest $request): Response
    {
        $promocodeId = $this->handler->handle($request->toCreateTariffPromocode());

        $this->flush();

        return $this->response($promocodeId);
    }
}
