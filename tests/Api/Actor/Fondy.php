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
            'headers' => [
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function orderPaid(array $markPaidByFondy): void
    {
        $response = $this->client->post('/order/markPaidByFondy', $markPaidByFondy);
        $this->assertResultMatchesPattern($response, ['data' => []]);
    }
}
