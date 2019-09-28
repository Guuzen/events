<?php

namespace App\Promocode\Action;

use App\Infrastructure\Http\AppController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PromocodeHttpAdapter extends AppController
{
    private $handler;

    public function __construct(PromocodeHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @Route("/admin/promocode/create", methods={"POST"})
     */
    public function createRegularPromocode(CreateRegularPromocode $createRegularPromocode): Response
    {
        $promocodeId = $this->handler->createRegularPromocode($createRegularPromocode);

        return $this->response($promocodeId);
    }
}
