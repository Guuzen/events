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

    /**
     * @var string
     */
    private $domain;

    public function __construct(AbstractBrowser $client, string $domain)
    {
        $this->client = $client;
        $this->domain = $domain;
    }

    public function placeOrder(array $placeOrderData): void
    {
        $this->client->xmlHttpRequest(
            'POST',
            '/order/place',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_HOST'    => $this->domain,
            ],
            json_encode($placeOrderData)
        );
    }

    public function seeOrderPlaced(): void
    {
        // TODO move to placeOrder
        WebTestCase::assertResponseIsSuccessful();
        $this->assertMatchesPattern('{
                "data": {
                    "id": "@uuid@"
                }
            }',
            $this->client->getResponse()->getContent()
        );
    }
}
