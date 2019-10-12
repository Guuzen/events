<?php

namespace App\Tests\Step\Api;

use App\Tests\ApiTester;
use App\Tests\Contract\AppRequest\Event\CreateEvent;
use App\Tests\Contract\AppRequest\Order\MarkOrderPaid;
use App\Tests\Contract\AppRequest\CreateFixedPromocode\CreateFixedPromocode;
use App\Tests\Contract\AppRequest\Tariff\CreateTariff;
use App\Tests\Contract\AppRequest\Ticket\CreateTicket;
use App\Tests\Contract\AppResponse\EmailWithTicket;
use App\Tests\Contract\AppResponse\EventById;
use App\Tests\Contract\AppResponse\EventInList;
use App\Tests\Contract\AppResponse\OrderById;
use App\Tests\Contract\AppResponse\OrderInList;
use App\Tests\Contract\AppResponse\TariffById\TariffById;
use App\Tests\Contract\AppResponse\TariffInList\TariffInList;
use App\Tests\Contract\AppResponse\TicketById;
use App\Tests\Contract\AppResponse\TicketInList;
use App\Tests\Contract\PromocodeInList\AppResponse\PromocodeInList;
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

    public function createsPromocode(CreateFixedPromocode $createPromocode): string
    {
        $I = $this;

        $I->sendPOST('/admin/promocode/createFixed', $createPromocode);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsId();

        return $I->grabIdFromResponse();
    }

    public function seePromocodeCreatedInList(string $eventId, PromocodeInList $promocodeInList): void
    {
        $I = $this;

        $I->sendGET('/admin/promocode/list', [
            'eventId' => $eventId,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([$promocodeInList]);
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
