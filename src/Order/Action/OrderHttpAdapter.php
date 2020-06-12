<?php

namespace App\Order\Action;

use App\Common\Error;
use App\Event\Model\EventId;
use App\Infrastructure\Http\AppController;
use App\Order\Model\OrderId;
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
    public function placeOrder(PlaceOrderRequest $placeOrderRequest, EventId $eventId): Response
    {
        // TODO create user must be idempotent. Maybe this method should be named in other way
        $userId = UserId::new();

        /** @var OrderId|Error $orderId */
        $orderId = $this->em->transactional(function () use ($placeOrderRequest, $userId, $eventId) {
            $orderId = $this->orderHandler->placeOrder($placeOrderRequest->toPlaceOrder((string)$userId, (string)$eventId));
            // TODO create user from frontend
            if ($orderId instanceof Error) {
                return $orderId;
            }

            $this->userHandler->createUser($placeOrderRequest->toCreateUser((string)$userId, $orderId));

            return $orderId;
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
}
