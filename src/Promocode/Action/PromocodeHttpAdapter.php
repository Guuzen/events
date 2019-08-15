<?php

namespace App\Promocode\Action;

use App\Common\AppController;
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
        [$promocodeId, $error] = $this->handler->createRegularPromocode($createRegularPromocode);

        if (null !== $error) {
            return $this->errorJson($error);
        }

        return $this->successJson($promocodeId);
    }
}
