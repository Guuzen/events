<?php

declare(strict_types=1);

namespace Tests\Api\Actor;

use Tests\Api\Infrastructure\ApiAssertions;
use Tests\Api\Infrastructure\Client;

final class Manager
{
    use ApiAssertions;

    /**
     * @var Client
     */
    private $client;

    public function __construct(string $loggerName = 'Manager')
    {
        $this->client = new Client('http://web', $loggerName, [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
        ]);
    }

    public function createsEvent(array $createEvent): string
    {
        $response = $this->client->post('/admin/event', $createEvent);
        $this->assertResultMatchesPattern($response, [
            'data' => '@uuid@',
        ]);

        return $response['data'];
    }

    public function seeEventInList(array $events): void
    {
        $response = $this->client->get('/admin/eventDomain', []);
        $this->assertResultMatchesPattern($response, $events);
    }

    public function seeEventById(string $eventId, array $event): void
    {
        $response = $this->client->get("/admin/eventDomain/$eventId", []);
        $this->assertResultMatchesPattern($response, $event);
    }


    public function createsTariff(array $createTariff): string
    {
        $response = $this->client->post('/admin/tariff', $createTariff);
        $this->assertResultMatchesPattern($response, [
            'data' => '@uuid@',
        ]);

        return $response['data'];
    }

    public function seeTariffInList(string $eventId, array $tariffs): void
    {
        $response = $this->client->get('/admin/tariff', [
            'eventId' => $eventId,
        ]);
        $this->assertResultMatchesPattern($response, $tariffs);
    }

    public function createTariffDescription(array $createTariffDescription): void
    {
        $response = $this->client->post('/admin/tariffDescription', $createTariffDescription);
        $this->assertResultMatchesPattern($response, ['data' => []]);
    }

    public function seeTariffDescriptionById(string $tariffId, array $tariffDescription): void
    {
        $response = $this->client->get("/admin/tariffDescription/$tariffId", []);
        $this->assertResultMatchesPattern($response, $tariffDescription);
    }

    public function seeTariffById(string $tariffId, array $tariff): void
    {
        $response = $this->client->get("/admin/tariff/$tariffId", []);
        $this->assertResultMatchesPattern($response, $tariff);
    }

    public function seeTicketInList(string $eventId, array $tickets): string
    {
        $response = $this->client->get('/admin/ticket/list', [
            'eventId' => $eventId,
        ]);
        $this->assertResultMatchesPattern($response, $tickets);

        return $response['data'][0]['id'];
    }

    public function seeTicketById(string $ticketId, array $ticket): void
    {
        $response = $this->client->get('/admin/ticket/show', [
            'ticketId' => $ticketId,
        ]);
        $this->assertResultMatchesPattern($response, $ticket);
    }

    public function seeOrderInList(string $eventId, array $orders): void
    {
        $response = $this->client->get('/admin/order', [
            'eventId' => $eventId,
        ]);
        $this->assertResultMatchesPattern($response, $orders);
    }

    public function seeOrderById(string $orderId, array $order): void
    {
        $response = $this->client->get("/admin/order/$orderId", []);
        $this->assertResultMatchesPattern($response, $order);
    }

    public function markOrderPaid(string $orderId, array $markOrderPaid): void
    {
        $response = $this->client->post("/admin/order/$orderId/markPaid", $markOrderPaid);
        $this->assertResultMatchesPattern($response, ['data' => []]);
    }

    public function createFixedPromocode(array $promocode): void
    {
        $response = $this->client->post('/admin/promocode/createTariff', $promocode);
        $this->assertResultMatchesPattern($response, [
            'data' => '@uuid@',
        ]);
    }

    public function seeFixedPromocodeInList(string $eventId, array $promocodes): void
    {
        $response = $this->client->get('/admin/promocode/list', [
            'eventId' => $eventId,
        ]);
        $this->assertResultMatchesPattern($response, $promocodes);
    }
}
