<?php

namespace App\Tests\Step\Api;

use App\Tests\ApiTester;
use DateTimeImmutable;

// TODO data builders ?
class Manager extends ApiTester
{
    public function createsFoo2019Event(): string
    {
        $I = $this;

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/admin/event/create', [
            'name'   => '2019 foo event',
            'domain' => '2019foo.event.com',
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsId();

        return $I->grabIdFromResponse();
    }

    public function seeEventInEventList(string $eventId): void
    {
        $I = $this;

        $I->sendGET('/admin/event/list');

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([
            'data' => [
                [
                    'id'     => $eventId,
                    'name'   => '2019 foo event',
                    'domain' => '2019foo.event.com',
                ],
            ],
        ]);
    }

    public function seeEventById(string $eventId): void
    {
        $I = $this;

        $I->sendGET('/admin/event/show', [
            'event_id' => $eventId,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([
            'data' => [
                'id'     => $eventId,
                'name'   => '2019 foo event',
                'domain' => '2019foo.event.com',
            ],
        ]);
    }

    public function createsTariff(string $eventId, string $productType): string
    {
        $I = $this;
        $I->sendPOST('/admin/tariff/create', [
            'event_id'     => $eventId,
            'product_type' => $productType,
            'price_net'    => [
                [
                    'price' => [
                        'amount'   => '200',
                        'currency' => 'RUB',
                    ],
                    'term'  => [
                        'start' => (new DateTimeImmutable('-1 day'))->format('Y-m-d H:i:s'),
                        'end'   => (new DateTimeImmutable('+1 day'))->format('Y-m-d H:i:s'),
                    ],
                ],
            ],
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsId();

        return $I->grabIdFromResponse();
    }

    public function seeTariffInTariffList(string $eventId, string $tariffId, string $productType): void
    {
        $I = $this;

        $I->sendGET('/admin/tariff/list', [
            'event_id' => $eventId,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseMatchesJsonType(['string:date'], '$.data[0].term_start');
        $I->seeResponseMatchesJsonType(['string:date'], '$.data[0].term_end');
        $I->seeResponseContainsJson([
            'data' => [
                [
                    'id'           => $tariffId,
                    'product_type' => $productType,
                    'price'        => '200 RUB',
                ],
            ],
        ]);
    }

    public function seeTariffById(string $tariffId, string $productType): void
    {
        $I = $this;

        $I->sendGET('/admin/tariff/show', [
            'tariff_id' => $tariffId,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([
            [
                'id'           => $tariffId,
                'product_type' => $productType,
                'price'        => '200 RUB',
            ],
        ]);
    }

    public function createsTicket(string $eventId, string $tariffId): string
    {
        $I = $this;

        $I->sendPOST('/admin/ticket/create', [
            'event_id'  => $eventId,
            'number'    => '10002000',
            'tariff_id' => $tariffId,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsId();

        return $I->grabIdFromResponse();
    }

    public function seeTicketInTicketList(string $eventId, string $ticketId, string $ticketType): void
    {
        $I = $this;

        $I->sendGET('/admin/ticket/list');

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseMatchesJsonType(['string:date'], '$.data[0].created_at');
        $I->seeResponseContainsJson([
            'data' => [
                [
                    'event_id' => $eventId,
                    'id'       => $ticketId,
                    'type'     => $ticketType,
                    'number'   => '10002000',
                    'reserved' => false,
                ],
            ],
        ]);
    }

    public function seeTicketById(string $eventId, string $ticketId, string $ticketType): void
    {
        $I = $this;

        $I->sendGET('/admin/ticket/show', [
            'ticket_id' => $ticketId,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([
            'data' => [
                'event_id' => $eventId,
                'id'       => $ticketId,
                'type'     => $ticketType,
                'number'   => '10002000',
                'reserved' => false,
            ],
        ]);
    }

    public function seeOrderInOrderList(string $orderId, string $eventId, string $tariffId, string $productId): void
    {
        $I = $this;

        $I->sendGET('/admin/order/list', [
            'event_id' => $eventId,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseMatchesJsonType(['string:uuid'], '$.data[0].user_id');
        $I->seeResponseMatchesJsonType(['string:uuid'], '$.data[0].id');
        $I->seeResponseMatchesJsonType(['string:date'], '$.data[0].maked_at');
        $I->seeResponseContainsJson([
            'data' => [
                [
                    'id'         => $orderId,
                    'product_id' => $productId,
                    'tariff_id'  => $tariffId,
                    'paid'       => false,
                    'product'    => 'silver_pass',
                    'phone'      => '+123456789',
                    'first_name' => 'john',
                    'last_name'  => 'Doe',
                    'email'      => 'john@email.com',
                    'sum'        => '200',
                    'currency'   => 'RUB',
                    'event_id'   => $eventId,
                    'cancelled'  => false,
                ],
            ],
        ]);
    }

    public function seeOrderById(string $orderId, string $eventId, string $tariffId, string $productId): void
    {
        $I = $this;

        $I->sendGET('/admin/order/show', [
            'order_id' => $orderId,
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([
            'data' => [
                'product_id' => $productId,
                'tariff_id'  => $tariffId,
                'paid'       => false,
                'product'    => 'silver_pass',
                'phone'      => '+123456789',
                'first_name' => 'john',
                'last_name'  => 'Doe',
                'email'      => 'john@email.com',
                'sum'        => '200',
                'currency'   => 'RUB',
                'event_id'   => $eventId,
                'cancelled'  => false,
            ],
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
}
