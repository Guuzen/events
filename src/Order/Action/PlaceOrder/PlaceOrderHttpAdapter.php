<?php

declare(strict_types=1);

namespace App\Order\Action\PlaceOrder;

use App\Event\Model\EventId;
use App\Infrastructure\Http\AppController\AppController;
use App\User\Action\UserHandler;
use App\User\Model\UserId;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PlaceOrderHttpAdapter extends AppController
{
    private $handler;

    private $userHandler;

    // TODO move coupling between create user and placing order to frontend ?
    public function __construct(PlaceOrderHandler $handler, UserHandler $userHandler)
    {
        $this->handler     = $handler;
        $this->userHandler = $userHandler;
    }

    /**
     * @Route("/order/place", methods={"POST"})
     */
    public function __invoke(PlaceOrderRequest $placeOrderRequest, EventId $eventId): Response
    {
        // TODO create user must be idempotent. Maybe this method should be named in other way
        $userId = UserId::new();

        $orderId = $this->handler->handle($placeOrderRequest->toPlaceOrder($userId, $eventId));

        // TODO create user from frontend
        $this->userHandler->createUser($placeOrderRequest->toCreateUser($userId, $orderId));

        $this->flush();

        return $this->response($orderId);
    }
}
