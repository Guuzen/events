<?php

namespace App\Product\Action;

use App\Infrastructure\Http\AppController;
use App\Common\Error;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ProductHttpAdapter extends AppController
{
    private $handler;

    public function __construct(ProductHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @Route("/admin/ticket/create", methods={"POST"})
     */
    public function createTicket(CreateTicket $createTicket): Response
    {
        $ticketId = $this->handler->createTicket($createTicket);

        if ($ticketId instanceof Error) {
            return $this->errorJson($ticketId);
        }

        return $this->successJson($ticketId);
    }
}
