<?php

declare(strict_types=1);

namespace App\Adapters\ClientApi\TicketOrder\PlaceTicketOrder;

use App\Infrastructure\Http\AppController\AppController;
use App\Model\Event\EventId;
use App\Model\Tariff\TariffId;
use App\Model\Tariff\Tariffs;
use App\Model\TariffDescription\TariffDescriptionId;
use App\Model\TariffDescription\TariffDescriptions;
use App\Model\TicketOrder\TicketOrder;
use App\Model\TicketOrder\TicketOrderId;
use App\Model\TicketOrder\TicketOrders;
use App\Model\User\Contacts;
use App\Model\User\FullName;
use App\Model\User\User;
use App\Model\User\UserId;
use App\Model\User\Users;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PlaceTicketOrderHttpAdapter extends AppController
{
    private $ticketOrders;

    private $tariffs;

    private $tariffDescriptions;

    private $users;

    public function __construct(
        TicketOrders $ticketOrders,
        Tariffs $tariffs,
        TariffDescriptions $tariffDescriptions,
        Users $users,
    )
    {
        $this->ticketOrders       = $ticketOrders;
        $this->tariffs            = $tariffs;
        $this->tariffDescriptions = $tariffDescriptions;
        $this->users              = $users;
    }

    /**
     * @Route("/ticketOrder/place", methods={"POST"})
     */
    public function __invoke(PlaceTicketOrderRequest $request, EventId $eventId): Response
    {
        // TODO create user must be idempotent. Maybe this method should be named in other way
        $userId = UserId::new();

        $orderDate = new \DateTimeImmutable();

        $tariffId          = new TariffId($request->tariffId);
        $tariff            = $this->tariffs->findById($tariffId, $eventId);
        $tariffDescription = $this->tariffDescriptions->getById(new TariffDescriptionId($request->tariffId));
        $ticketOrderId     = TicketOrderId::new();
        $ticketOrder       = new TicketOrder(
            $ticketOrderId,
            $tariffId,
            $eventId,
            $userId,
            $tariff->calculatePrice($orderDate),
            $tariffDescription->tariffType,
            $orderDate,
        );

        $this->ticketOrders->add($ticketOrder);

        // TODO create user from frontend ?
        $user = new User(
            $userId,
            new FullName($request->firstName, $request->lastName),
            new Contacts($request->email, $request->phone)
        );
        $this->users->add($user);

        $this->flush();

        return $this->response(['id' => (string)$ticketOrderId]);
    }
}