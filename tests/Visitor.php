<?php

namespace App\Tests;

use Coduo\PHPMatcher\PHPUnit\PHPMatcherAssertions;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

final class Visitor
{
    use PHPMatcherAssertions;

    /**
     * @var AbstractBrowser
     */
    private $client;

    public function __construct(AbstractBrowser $client)
    {
        $this->client = $client;
    }

    public function visitorPlaceOrder(array $placeOrderData): void
    {
        $this->client->xmlHttpRequest(
            'POST',
            '/order_ticket_by_wire',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($placeOrderData)
        );
    }

    public function visitorSeeOrderPlaced(): void
    {
        WebTestCase::assertResponseIsSuccessful();
        $this->assertMatchesPattern('{
                "data": []
            }',
            $this->client->getResponse()->getContent()
        );
    }
}
