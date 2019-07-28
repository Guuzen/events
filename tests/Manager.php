<?php

namespace App\Tests;

use Coduo\PHPMatcher\PHPUnit\PHPMatcherAssertions;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

final class Manager
{
    use PHPMatcherAssertions;

    private const EVENT_ID = 'ac28bf81-08c6-4fc0-beae-7d4aabf1396e';

    private $client;

    public function __construct(AbstractBrowser $client)
    {
        $this->client = $client;
    }

    public function managerSeeOrderPlaced(array $orderData): void
    {
        $this->client->xmlHttpRequest('GET', '/admin/orders?event_id=' . self::EVENT_ID);

        WebTestCase::assertResponseIsSuccessful();
        $this->assertMatchesPattern(json_encode([
            'data' => [$orderData],
        ]), $this->client->getResponse()->getContent());
    }
}
