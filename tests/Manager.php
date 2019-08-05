<?php

namespace App\Tests;

use Coduo\PHPMatcher\PHPUnit\PHPMatcherAssertions;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

// TODO HATEOAS ?
final class Manager
{
    use PHPMatcherAssertions;

    private $client;

    public function __construct(AbstractBrowser $client)
    {
        $this->client = $client;
    }

    public function seesOrderPlaced(array $orderData, string $eventId): void
    {
        $this->client->xmlHttpRequest('GET', '/admin/order/list', [
            'eventId' => $eventId,
        ]);

        WebTestCase::assertResponseIsSuccessful();
        $this->assertMatchesPattern(json_encode([
            'data' => [$orderData],
        ]), $this->client->getResponse()->getContent(), 'manager cant see order is placed');
    }

    // TODO error messages on assets
    public function createsEvent(array $eventData): string
    {
        $this->client->xmlHttpRequest(
            'POST',
            '/admin/event/create',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($eventData)
        );

        WebTestCase::assertResponseIsSuccessful('manager got unsuccessful response while creates event');
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertMatchesPattern(json_encode([
            'data' => [
                'id' => '@uuid@',
            ],
        ]), $this->client->getResponse()->getContent(), 'manager cant create event');

        return $responseData['data']['id'];
    }

    public function seesEventCreated(array $eventData, string $eventId): void
    {
        $this->client->xmlHttpRequest('GET', '/admin/event/list', [
            'eventId' => $eventId,
        ]);

        WebTestCase::assertResponseIsSuccessful();
        $this->assertMatchesPattern(json_encode([
            'data' => [
                $eventData,
            ],
        ]), $this->client->getResponse()->getContent());
    }

    public function createsTariff(array $tariffData): string
    {
        $this->client->xmlHttpRequest(
            'POST',
            '/admin/tariff/create',
            [],
            [],
            [],
            json_encode($tariffData)
        );

        WebTestCase::assertResponseIsSuccessful('manager got unsuccessful response while creates tariff');
        $this->assertMatchesPattern(json_encode([
            'data' => [
                'id' => '@uuid@',
            ],
        ]), $this->client->getResponse()->getContent(), 'manager cant create tariff');
        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        return $responseData['data']['id'];
    }

    // TODO rename to seesTariffInLinst + rename messages accrodingly
    public function seesTariffCreated(array $tariffDataPattern, string $eventId): void
    {
        $this->client->xmlHttpRequest('GET', '/admin/tariff/list', [
            'eventId' => $eventId,
        ]);

        WebTestCase::assertResponseIsSuccessful('manager got unsuccessful response while trying to see tariff is created');
        $this->assertMatchesPattern(json_encode([
            'data' => [
                $tariffDataPattern,
            ],
        ]), $this->client->getResponse()->getContent(), 'manager cant see tariff is created');
    }

    public function createsTicket(array $ticketData): string
    {
        $this->client->request(
            'POST',
            '/admin/ticket/create',
            [],
            [],
            [],
            json_encode($ticketData)
        );

        WebTestCase::assertResponseIsSuccessful('manager got unsuccessful response while creates ticket');
        $this->assertMatchesPattern(json_encode([
            'data' => [
                'id' => '@uuid@',
            ],
        ]), $this->client->getResponse()->getContent(), 'manager cant create ticket');
        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        return $responseData['data']['id'];
    }

    public function seesProductCreated($productDataPattern, string $eventId): void
    {
        $this->client->xmlHttpRequest('GET', '/admin/product/list');

        WebTestCase::assertResponseIsSuccessful();
        $this->assertMatchesPattern(json_encode([
            'data' => [
                $productDataPattern,
            ],
        ]), $this->client->getResponse()->getContent(), 'manager cant see product is created');
    }
}
