<?php

declare(strict_types=1);

namespace App\Order\Action\PlaceOrder;

use App\Common\Error;
use App\Event\Model\EventId;
use App\Infrastructure\Http\AppController;
use App\Order\Model\OrderId;
use App\User\Action\UserHandler;
use App\User\Model\UserId;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PlaceOrderHttpAdapter extends AppController
{
    private $em;

    private $handler;

    private $userHandler;

    // TODO move coupling between create user and placing order to frontend ?
    public function __construct(EntityManagerInterface $em, PlaceOrderHandler $handler, UserHandler $userHandler)
    {
        $this->em          = $em;
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

        /** @var OrderId|Error $orderId */
        $orderId = $this->em->transactional(function () use ($placeOrderRequest, $userId, $eventId) {
            $orderId = $this->handler->handle($placeOrderRequest->toPlaceOrder($userId, $eventId));
            // TODO create user from frontend
            if ($orderId instanceof Error) {
                return $orderId;
            }

            $this->userHandler->createUser($placeOrderRequest->toCreateUser($userId, $orderId));

            return $orderId;
        });

        return $this->response($orderId);
    }
}
