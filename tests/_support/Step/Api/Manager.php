<?php

namespace App\Tests\Step\Api;

use App\Tests\ApiTester;
use App\Tests\AppRequest\Event\CreateEvent;
use App\Tests\AppRequest\Order\MarkOrderPaid;
use App\Tests\AppRequest\Tariff\CreateTariff;
use App\Tests\AppRequest\Ticket\CreateTicket;
use App\Tests\AppResponse\EmailWithTicket;
use App\Tests\AppResponse\EventById;
use App\Tests\AppResponse\EventInList;
use App\Tests\AppResponse\OrderById;
use App\Tests\AppResponse\OrderInList;
use App\Tests\AppResponse\TariffById\TariffById;
use App\Tests\AppResponse\TariffInList\TariffInList;
use App\Tests\AppResponse\TicketById;
use App\Tests\AppResponse\TicketInList;
use DateTimeImmutable;

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

    public function seeEventInList(EventInList $event): void
    {
        $I = $this;

        $I->sendGET('/admin/event/list');

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([$event]);
    }

    public function seeEventById(string $eventId, EventById $event): void
    {
        $I = $this;

        $I->sendGET('/admin/event/show', [
            'eventId' => $eventId,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson($event);
    }

    public function createsTariff(CreateTariff $createTariff): string
    {
        $I = $this;
        $I->sendPOST('/admin/tariff/create', $createTariff);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsId();

        return $I->grabIdFromResponse();
    }

    public function seeTariffInList(string $eventId, TariffInList $tariff): void
    {
        $I = $this;

        $I->sendGET('/admin/tariff/list', [
            'eventId' => $eventId,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([$tariff]);
    }

    public function seeTariffById(string $tariffId, TariffById $tariff): void
    {
        $I = $this;

        $I->sendGET('/admin/tariff/show', [
            'tariffId' => $tariffId,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson($tariff);
    }

    public function createsTicket(CreateTicket $createTicket): string
    {
        $I = $this;

        $I->sendPOST('/admin/ticket/create', $createTicket);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsId();

        return $I->grabIdFromResponse();
    }

    public function seeTicketInList(string $eventId, TicketInList $ticket): void
    {
        $I = $this;

        $I->sendGET('/admin/ticket/list', [
            'eventId' => $eventId,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([$ticket]);
    }

    public function seeTicketById(string $ticketId, TicketById $ticket): void
    {
        $I = $this;

        $I->sendGET('/admin/ticket/show', [
            'ticketId' => $ticketId,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson($ticket);
    }

    public function seeOrderInList(string $eventId, OrderInList $order): void
    {
        $I = $this;

        $I->sendGET('/admin/order/list', [
            'eventId' => $eventId,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([$order]);
    }

    public function seeOrderById(string $orderId, OrderById $order): void
    {
        $I = $this;

        $I->sendGET('/admin/order/show', [
            'orderId' => $orderId,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson($order);
    }

    public function createsPromocode(string $eventId, string $tariffId): string
    {
        $I = $this;

        $I->sendPOST('/admin/promocode/create', [
            'eventId'         => $eventId,
            'discount'        => [
                'amount'   => '100',
                'currency' => 'RUB',
            ],
            'type'            => 'regular',
            'useLimit'        => 1,
            'expireAt'        => (new DateTimeImmutable('tomorrow'))->format('Y-m-d H:i:s'),
            'allowedTariffs'  => [$tariffId],
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
                'eventId'         => $eventId,
                'discount'        => [
                    'amount'   => '100',
                    'currency' => 'RUB',
                ],
                'type'            => 'regular',
                'useLimit'        => 1,
                'expireAt'        => (new DateTimeImmutable('tomorrow'))->format('Y-m-d H:i:s'),
                'allowedTariffs'  => [$tariffId],
                'usable'          => true,
            ],
        ]);
    }

    public function markOrderPaid(MarkOrderPaid $markOrderPaid): void
    {
        $I = $this;

        $I->insulate();
        $I->haveHttpHeader('Content-Type', 'application/json');

        $I->sendPOST('/admin/order/markPaid', $markOrderPaid);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([]);
    }

    public function seeEmailWithTicketSent(EmailWithTicket $expectedEmail): void
    {
        $I = $this;
        $I->insulate();

        $I->seeEmailIsSent(1);
        $email       = $I->grabEmailMessages()[0];
        $actualEmail = new EmailWithTicket(
            $email->getSubject(),
            key($email->getFrom()),
            key($email->getTo())
        );
        $I->assertEquals($expectedEmail, $actualEmail);
    }
}
