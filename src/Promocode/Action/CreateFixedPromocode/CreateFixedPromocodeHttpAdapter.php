<?php

namespace App\Promocode\Action\CreateFixedPromocode;

use App\Infrastructure\Http\AppController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CreateFixedPromocodeHttpAdapter extends AppController
{
    private $handler;

    private $em;

    public function __construct(CreateFixedPromocodeHandler $handler, EntityManagerInterface $em)
    {
        $this->handler = $handler;
        $this->em      = $em;
    }

    /**
     * @Route("/admin/promocode/createFixed", methods={"POST"})
     */
    public function createRegularPromocode(CreateFixedPromocodeRequest $request): Response
    {
        $promocodeId = $this->handler->handle($request->toCreateFixedPromocode());

        $this->em->flush();

        return $this->response($promocodeId);
    }
}
