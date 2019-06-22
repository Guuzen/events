<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class MakeOrderTest extends WebTestCase
{
    public function testOrderTicketByWire()
    {
        $client = self::createClient();

        $client->xmlHttpRequest('POST', '/registration/make_order', [
            'first_name'     => 'john',
            'last_name'      => 'Doe',
            'email'          => 'john@email.com',
            'payment_method' => 'wire',
            'tariff'         => 'silver',
        ]);
    }
}
