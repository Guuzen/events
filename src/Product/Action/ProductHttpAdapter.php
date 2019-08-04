<?php

namespace App\Product\Action;

use App\Common\AppController;
use App\Queries\FindEventIdByDomain;
use Symfony\Component\HttpFoundation\Request;
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
        [$ticketId, $error]    = $this->handler->createTicket($createTicket);

        if (null !== $error) {
            return $this->errorJson($error);
        }

        return $this->successJson(['id' => (string) $ticketId]);
    }
}
