<?php

namespace App\Tests\Step\Api;

use App\Tests\ApiTester;

use App\Tests\AppRequest\Event\CreateEvent;
use App\Tests\AppRequest\MarkOrderPaid\MarkOrderPaid;
use App\Tests\AppRequest\Tariff\CreateTariff;
use App\Tests\AppRequest\Ticket\CreateTicket;
use App\Tests\AppResponse\EventById\EventById;
use App\Tests\AppResponse\EventInList\EventInList;
use App\Tests\AppResponse\OrderById\OrderById;
use App\Tests\AppResponse\OrderInOrderList\OrderInOrderList;
use App\Tests\AppResponse\TariffById\TariffById;
use App\Tests\AppResponse\TariffInList\TariffInList;
use App\Tests\AppResponse\TicketById\TicketById;
use App\Tests\AppResponse\TicketInTicketList\TicketInTicketList;
use DateTimeImmutable;

// TODO group list + show to one method
class Manager extends ApiTester
{
    public function createsEvent(CreateEvent $createEvent): string
    {
        $I = $this;

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/admin/event/create', $createEvent);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsId();

        return $I->grabIdFromResponse();
    }

    public function seeEventInEventList(EventInList $event): void
    {
        $I = $this;

        $I->sendGET('/admin/event/list');

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([
            'data' => [$event->jsonSerialize()],
        ]);
    }

    public function seeEventById(string $eventId, EventById $eventById): void
    {
        $I = $this;

        $I->sendGET('/admin/event/show', [
            'event_id' => $eventId,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([
            'data' => $eventById->jsonSerialize(),
        ]);
    }

    public function createsTariff(CreateTariff $createTariff): string
    {
        $I = $this;
        $I->sendPOST('/admin/tariff/create', $createTariff);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsId();

        return $I->grabIdFromResponse();
    }

    public function seeTariffInTariffList(string $eventId, TariffInList $tariff): void
    {
        $I = $this;

        $I->sendGET('/admin/tariff/list', [
            'event_id' => $eventId,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([
            'data' => [$tariff->jsonSerialize()],
        ]);
    }

    public function seeTariffById(string $tariffId, TariffById $tariff): void
    {
        $I = $this;

        $I->sendGET('/admin/tariff/show', [
            'tariff_id' => $tariffId,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([$tariff->jsonSerialize()]);
    }

    public function createsTicket(CreateTicket $createTicket): string
    {
        $I = $this;

        $I->sendPOST('/admin/ticket/create', $createTicket);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsId();

        return $I->grabIdFromResponse();
    }

    public function seeTicketInTicketList(string $eventId, TicketInTicketList $ticket): void
    {
        $I = $this;

        $I->sendGET('/admin/ticket/list', [
            'event_id' => $eventId,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([
            'data' => [$ticket->jsonSerialize()],
        ]);
    }

    public function seeTicketById(string $ticketId, TicketById $ticket): void
    {
        $I = $this;

        $I->sendGET('/admin/ticket/show', [
            'ticket_id' => $ticketId,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson(['data' => $ticket->jsonSerialize()]);
    }

    public function seeOrderInOrderList(string $eventId, OrderInOrderList $order): void
    {
        $I = $this;

        $I->sendGET('/admin/order/list', [
            'event_id' => $eventId,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([
            'data' => [$order->jsonSerialize()],
        ]);
    }

    public function seeOrderById(string $orderId, OrderById $order): void
    {
        $I = $this;

        $I->sendGET('/admin/order/show', [
            'order_id' => $orderId,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([
            'data' => $order->jsonSerialize(),
        ]);
    }

    public function createsPromocode(string $eventId, string $tariffId): string
    {
        $I = $this;

        $I->sendPOST('/admin/promocode/create', [
            'event_id'        => $eventId,
            'discount'        => [
                'amount'   => '100',
                'currency' => 'RUB',
            ],
            'type'            => 'regular',
            'use_limit'       => 1,
            'expire_at'       => (new DateTimeImmutable('tomorrow'))->format('Y-m-d H:i:s'),
            'allowed_tariffs' => [$tariffId],
            'usable'          => true,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsId();

        return $I->grabIdFromResponse();
    }

    // TODO in list?
    public function seePromocodeCreated(string $eventId, string $tariffId, string $promocodeId): void
    {
        $I = $this;

        $I->sendGET('/admin/promocode/list');

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([
            'data' => [
                'id'              => $promocodeId,
                'event_id'        => $eventId,
                'discount'        => [
                    'amount'   => '100',
                    'currency' => 'RUB',
                ],
                'type'            => 'regular',
                'use_limit'       => 1,
                'expire_at'       => (new DateTimeImmutable('tomorrow'))->format('Y-m-d H:i:s'),
                'allowed_tariffs' => [$tariffId],
                'usable'          => true,
            ],
        ]);
    }

    public function markOrderPaid(MarkOrderPaid $markOrderPaid): void
    {
        $I = $this;

        // TODO extract api requests to Api or helper ?
        $I->sendPOST('/admin/order/mark_paid', $markOrderPaid);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([
            'data' => [],
        ]);
    }
}
