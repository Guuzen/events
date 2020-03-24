<?php

namespace App\Order\Action;

use App\Infrastructure\Http\AppController;
use App\User\Action\UserHandler;
use App\User\Model\UserId;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class OrderHttpAdapter extends AppController
{
    private $orderHandler;

    private $userHandler;

    private $em;

    public function __construct(OrderHandler $orderHandler, UserHandler $userHandler, EntityManagerInterface $em)
    {
        $this->orderHandler = $orderHandler;
        $this->userHandler  = $userHandler;
        $this->em           = $em;
    }

    /**
     * @Route("/order/place", methods={"POST"})
     */
    public function placeOrder(PlaceOrderRequest $placeOrderRequest): Response
    {
        // TODO create user must be idempotent. Maybe this method should be named in other way
        $userId  = UserId::new();
        $orderId = $this->em->transactional(function () use ($placeOrderRequest, $userId) {
            $this->userHandler->createUser($placeOrderRequest->toCreateUser((string)$userId));

            return $this->orderHandler->placeOrder($placeOrderRequest->toPlaceOrder((string)$userId));
        });

        return $this->response($orderId);
    }

    /**
     * @Route("/admin/order/markPaid", methods={"POST"})
     */
    public function markOrderPaid(MarkOrderPaid $markOrderPaid): Response
    {
        $result = $this->orderHandler->markOrderPaid($markOrderPaid);

        return $this->response($result);
    }

    /**
     * @Route("/order/payByCard", methods={"POST"})
     */
    public function payByCard(PayByCard $payByCard): Response
    {
        $paymentUrl = $this->orderHandler->payByCard($payByCard);

        return $this->response($paymentUrl);
    }

    /**
     * @Route("/order/markPaidByFondy", methods={"POST"})
     */
    public function markOrdePaidByFondy(MarkOrderPaidByFondy $markOrderPaidByFondy): Response
    {
        $result = $this->orderHandler->markOrderPaidByFondy($markOrderPaidByFondy);

        return $this->response($result);
    }
}
