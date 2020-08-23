<?php

declare(strict_types=1);

namespace Tests\Api\Actor;

use Tests\Api\Infrastructure\ApiAssertions;
use Tests\Api\Infrastructure\Client;

final class Fondy
{
    use ApiAssertions;

    /**
     * @var Client
     */
    private $client;

    public function __construct(string $loggerName = 'Fondy')
    {
        $this->client = new Client('http://2019foo.event.com', $loggerName, [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
        ]);
    }

    public function orderPaid(string $orderId): void
    {
        $response = $this->client->post("/order/$orderId/markPaidByFondy", []);
        $this->assertResultMatchesPattern($response, ['data' => []]);
    }
}
