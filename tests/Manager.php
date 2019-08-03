<?php

namespace App\Tests;

use Coduo\PHPMatcher\PHPUnit\PHPMatcherAssertions;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

// TODO HATEOAS ?
final class Manager
{
    use PHPMatcherAssertions;

    private const EVENT_ID = 'ac28bf81-08c6-4fc0-beae-7d4aabf1396e';

    private $client;

    public function __construct(AbstractBrowser $client)
    {
        $this->client = $client;
    }

    public function seeOrderPlaced(array $orderData): void
    {
        $this->client->xmlHttpRequest('GET', '/admin/orders?event_id=' . self::EVENT_ID);

        WebTestCase::assertResponseIsSuccessful();
        $this->assertMatchesPattern(json_encode([
            'data' => [$orderData],
        ]), $this->client->getResponse()->getContent());
    }

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

        WebTestCase::assertResponseIsSuccessful();
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertMatchesPattern(json_encode([
            'data' => [
                'id' => '@uuid@',
            ],
        ]), $this->client->getResponse()->getContent());

        return $responseData['data']['id'];
    }

    public function seesEventCreated(array $eventData): void
    {
        $this->client->xmlHttpRequest('GET', '/admin/event/list');

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

        WebTestCase::assertResponseIsSuccessful();
        $this->assertMatchesPattern(json_encode([
            'data' => [
                'id' => '@uuid@',
            ],
        ]), $this->client->getResponse()->getContent());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        return $responseData['data']['id'];
    }

    public function seesTariffCreated(array $tariffDataPattern): void
    {
        $this->client->xmlHttpRequest('GET', '/admin/tariff/list');

        WebTestCase::assertResponseIsSuccessful();
        $this->assertMatchesPattern(json_encode([
            'data' => [
                $tariffDataPattern,
            ],
        ]), $this->client->getResponse()->getContent());
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

        WebTestCase::assertResponseIsSuccessful();
        $this->assertMatchesPattern(json_encode([
            'data' => [
                'id' => '@uuid@',
            ],
        ]), $this->client->getResponse()->getContent());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        return $responseData['data']['id'];
    }

    public function seesProductCreated($productDataPattern): void
    {
        $this->client->xmlHttpRequest('GET', '/admin/product/list');

        WebTestCase::assertResponseIsSuccessful();
        $this->assertMatchesPattern(json_encode([
            'data' => [
                $productDataPattern,
            ],
        ]), $this->client->getResponse()->getContent());
    }
}
