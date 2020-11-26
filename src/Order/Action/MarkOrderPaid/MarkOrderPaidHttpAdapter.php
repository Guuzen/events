<?php

namespace App\Order\Action\MarkOrderPaid;

use App\Infrastructure\Http\AppController\AppController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class MarkOrderPaidHttpAdapter extends AppController
{
    private $orderHandler;

    public function __construct(MarkOrderPaidHandler $orderHandler)
    {
        $this->orderHandler = $orderHandler;
    }

    /**
     * @Route("/admin/order/{orderId}/markPaid", methods={"POST"})
     */
    public function markOrderPaid(MarkOrderPaidRequest $markOrderPaidRequest): Response
    {
        $this->orderHandler->markOrderPaid($markOrderPaidRequest->createMarkOrderPaid());

        $this->flush();

        return $this->response([]);
    }
}
