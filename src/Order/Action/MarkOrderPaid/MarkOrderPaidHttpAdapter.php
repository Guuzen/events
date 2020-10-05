<?php

namespace App\Order\Action\MarkOrderPaid;

use App\Infrastructure\Http\AppController\AppController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class MarkOrderPaidHttpAdapter extends AppController
{
    private $orderHandler;

    private $em;

    public function __construct(MarkOrderPaidHandler $orderHandler, EntityManagerInterface $em)
    {
        $this->orderHandler = $orderHandler;
        $this->em           = $em;
    }

    /**
     * @Route("/admin/order/{orderId}/markPaid", methods={"POST"})
     */
    public function markOrderPaid(MarkOrderPaidRequest $markOrderPaidRequest): Response
    {
        $this->orderHandler->markOrderPaid($markOrderPaidRequest->createMarkOrderPaid());

        $this->em->flush();

        return $this->response([]);
    }
}
