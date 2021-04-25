<?php

declare(strict_types=1);

namespace App\Adapters\ClientApi\Order\PlaceOrder;

use App\Model\Event\EventId;
use App\Model\Event\Events;
use App\Infrastructure\Http\AppController\AppController;
use App\Model\Order\OrderId;
use App\Model\Order\Orders;
use App\Model\Tariff\TariffId;
use App\Model\Tariff\Tariffs;
use App\Model\User\Contacts;
use App\Model\User\FullName;
use App\Model\User\User;
use App\Model\User\UserId;
use App\Model\User\Users;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PlaceOrderHttpAdapter extends AppController
{
    // TODO move coupling between create user and placing order to frontend ?
    private $events;

    private $orders;

    private $tariffs;

    private $users;

    public function __construct(Events $events, Orders $orders, Tariffs $tariffs, Users $users)
    {
        $this->events  = $events;
        $this->orders  = $orders;
        $this->tariffs = $tariffs;
        $this->users   = $users;
    }

    /**
     * @Route("/order/place", methods={"POST"})
     */
    public function __invoke(PlaceOrderRequest $request, EventId $eventId): Response
    {
        // TODO create user must be idempotent. Maybe this method should be named in other way
        $userId = UserId::new();

        $orderDate = new \DateTimeImmutable();

        $event   = $this->events->findById($eventId);
        $tariff  = $this->tariffs->findById(new TariffId($request->tariffId), $eventId);
        $price   = $tariff->calculatePrice($orderDate); // TODO price calculation shold be here? Too much actions in controller
        $orderId = OrderId::new();
        $order   = $event->makeOrder(
            $orderId,
            $tariff,
            $userId,
            $price,
            $orderDate
        );

        $this->orders->add($order);

        // TODO create user from frontend ?
        $user = new User(
            $userId,
            $orderId,
            new FullName($request->firstName, $request->lastName),
            new Contacts($request->email, $request->phone)
        );
        $this->users->add($user);

        $this->flush();

        return $this->validateResponse($orderId);
    }
}
