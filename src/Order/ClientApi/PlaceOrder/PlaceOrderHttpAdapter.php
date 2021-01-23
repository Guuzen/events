<?php

declare(strict_types=1);

namespace App\Order\ClientApi\PlaceOrder;

use App\Event\Model\EventId;
use App\Event\Model\Events;
use App\Infrastructure\Http\AppController\AppController;
use App\Order\Model\OrderId;
use App\Order\Model\Orders;
use App\Tariff\Model\TariffId;
use App\Tariff\Model\Tariffs;
use App\User\Model\Contacts;
use App\User\Model\FullName;
use App\User\Model\User;
use App\User\Model\UserId;
use App\User\Model\Users;
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

        return $this->response($orderId);
    }
}
