<?php

namespace App\Product\Action;

use App\Common\AppController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ProductHttpAdapter extends AppController
{
    private $handler;

    public function __construct(ProductHandler $handler)
    {
        $this->handler             = $handler;
    }

    /**
     * @Route("/admin/ticket/create", methods={"POST"})
     */
    public function createTicket(CreateTicket $createTicket): Response
    {
        $result = $this->handler->createTicket($createTicket);

        if ($result->isErr()) {
            return $this->errorJson($result);
        }

        return $this->successJson($result->value());
    }
}
