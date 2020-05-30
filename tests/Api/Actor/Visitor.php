<?php

declare(strict_types=1);

namespace Tests\Api\Actor;

use InterNations\Component\HttpMock\PHPUnit\HttpMockTrait;
use PHPUnit\Framework\Assert;
use Tests\Api\Infrastructure\ApiAssertions;
use Tests\Api\Infrastructure\Client;

final class Visitor
{
    use ApiAssertions;
    use HttpMockTrait;

    /**
     * @var Client
     */
    private $client;

    public function __construct(string $loggerName = 'Visitor')
    {
        $this->client = new Client('http://2019foo.event.com', $loggerName, [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
        ]);
        static::setUpHttpMockBeforeClass(8001);
        $this->setUpHttpMock();
    }

    protected function assertSame($expected, $actual, string $message = ''): void
    {
        Assert::assertSame($expected, $actual, $message);
    }

    public function placeOrder(array $placeOrder): string
    {
        $response = $this->client->post('/order/place', $placeOrder);
        $this->assertResultMatchesPattern($response, [
            'data' => [
                'id' => '@uuid@',
            ]
        ]);

        return $response['data']['id'];
    }

    public function usePromocode($promocode): void
    {
        $response = $this->client->post('/promocode/use', $promocode);
        $this->assertResultMatchesPattern($response, [
            'data' => [],
        ]);
    }

    public function payOrderByCard(array $payOrderByCard): string
    {
        $response = $this->client->post('/order/payByCard', $payOrderByCard);
        $this->assertResultMatchesPattern($response, [
            'data' => '@string@',
        ]);

        return $response['data'];
    }

    public function awaitsForEmailWithTicket(): void
    {
        $this->http->mock
            ->when()
            ->methodIs('POST')
            ->pathIs('/send_ticket_email')
            ->then()
            ->statusCode(200)
            ->end();
        $this->http->setUp();
    }

    public function receivesEmailWithTicket(array $email): void
    {
        $response    = $this->http->requests->latest()->getBody();
        $actualEmail = \json_decode((string)$response, true);

        Assert::assertEquals($email, $actualEmail);
    }

    public function tearDown(): void
    {
        $this->tearDownHttpMock();
        static::tearDownHttpMockAfterClass();
    }
}
