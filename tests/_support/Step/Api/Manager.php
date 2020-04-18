<?php

namespace App\Tests\Step\Api;

use App\Tests\ApiTester;
use App\Tests\Contract\AppRequest\CreateFixedPromocode\CreateFixedPromocode;
use App\Tests\Contract\AppResponse\EmailWithTicket;
use App\Tests\Contract\AppResponse\PromocodeInList\PromocodeInList;

class Manager extends ApiTester
{
    public function createsEvent(array $createEvent): string
    {
        $I = $this;

        $I->sendPostJson('/admin/event/create', $createEvent);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsId();

        return $I->grabIdFromResponse();
    }

    public function seeEventInList(array $events): void
    {
        $I = $this;

        $I->sendGET('/admin/event/list');

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson($events);
    }

    public function seeEventById(string $eventId, array $event): void
    {
        $I = $this;

        $I->sendGET('/admin/event/show', [
            'eventId' => $eventId,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson($event);
    }

    public function createsTariff(array $createTariff): string
    {
        $I = $this;
        $I->sendPOST('/admin/tariff/create', $createTariff);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsId();

        return $I->grabIdFromResponse();
    }

    public function seeTariffInList(string $eventId, array $tariffs): void
    {
        $I = $this;

        $I->sendGET('/admin/tariff/list', [
            'eventId' => $eventId,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson($tariffs);
    }

    public function seeTariffById(string $tariffId, array $tariff): void
    {
        $I = $this;

        $I->sendGET('/admin/tariff/show', [
            'tariffId' => $tariffId,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson($tariff);
    }

    public function createsTicket(array $createTicket): string
    {
        $I = $this;

        $I->sendPOST('/admin/ticket/create', $createTicket);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsId();

        return $I->grabIdFromResponse();
    }

    public function seeTicketInList(string $eventId, array $tickets): void
    {
        $I = $this;

        $I->sendGET('/admin/ticket/list', [
            'eventId' => $eventId,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson($tickets);
        $I->seeResponseMatchesJsonType([
            'createdAt'   => 'string:date',
            'deliveredAt' => 'string:date|null',
        ], '$.data[0]');
    }

    public function seeTicketById(string $ticketId, array $ticket): void
    {
        $I = $this;

        $I->sendGET('/admin/ticket/show', [
            'ticketId' => $ticketId,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson($ticket);
        $I->seeResponseMatchesJsonType([
            'createdAt'   => 'string:date',
            'deliveredAt' => 'string:date|null',
        ], '$.data');
    }

    public function seeOrderInList(string $eventId, array $orders): void
    {
        $I = $this;

        $I->sendGET('/admin/order/list', [
            'eventId' => $eventId,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson($orders);
        $I->seeResponseMatchesJsonType([
            'productId'   => 'string:uuid',
            'userId'      => 'string:uuid',
            'makedAt'     => 'string:date',
            'deliveredAt' => 'string:date|null',
        ], '$.data[0]');
    }

    public function seeOrderById(string $orderId, array $order): void
    {
        $I = $this;

        $I->sendGET('/admin/order/show', [
            'orderId' => $orderId,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson($order);
        $I->seeResponseMatchesJsonType([
            'productId' => 'string:uuid',
            'userId'    => 'string:uuid',
            'makedAt'   => 'string:date',
        ], '$.data');
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

    public function markOrderPaid(array $markOrderPaid): void
    {
        $I = $this;

        $I->haveHttpHeader('Content-Type', 'application/json');

        $I->sendPOST('/admin/order/markPaid', $markOrderPaid);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([]);
    }

    public function seeEmailWithTicketSent(EmailWithTicket $expectedEmail): void
    {
        $I = $this;
        // TODO check email ?
//        $I->seeEmailIsSent(1);
//        $email       = $I->grabEmailMessages()[0];
//        $actualEmail = new EmailWithTicket(
//            $email->getSubject(),
//            key($email->getFrom()),
//            key($email->getTo())
//        );
//        $I->assertEquals($expectedEmail, $actualEmail);
    }
}
