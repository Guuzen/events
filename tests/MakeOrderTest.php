<?php

namespace App\Tests;

use Coduo\PHPMatcher\PHPUnit\PHPMatcherAssertions;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MakeOrderTest extends WebTestCase
{
    use PHPMatcherAssertions;

    private const EVENT_ID = 'ac28bf81-08c6-4fc0-beae-7d4aabf1396e';

    public function testOrderTicketByWire(): void
    {
        $visitor = self::createClient();
        $visitor->xmlHttpRequest('POST', '/order_ticket_by_wire', [], [], ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'firstName'     => 'john',
                'lastName'      => 'Doe',
                'email'         => 'john@email.com',
                'paymentMethod' => 'wire',
                'tariff'        => 'silver',
            ])
        );

        self::assertResponseIsSuccessful();
        $this->assertMatchesPattern('{
                "data": []
            }',
            $visitor->getResponse()->getContent()
        );

        ////////////////////////////////////////////////////////////////
//        $admin = self::createClient();
//        $admin->xmlHttpRequest('GET', '/admin/orders?event_id=' . self::EVENT_ID);
//
//        self::assertResponseIsSuccessful();
//        $this->assertMatchesPattern('{
//                "data": [
//                    {
//                        "id": "@uuid@",
//                        "first_name": "john",
//                        "last_name": "Doe",
//                        "email": "john@email.com",
//                        "payment_method": "wire",
//                        "tairff": "silver",
//                        "paid": false,
//                        "created_at": "@string@.isDateTime()"
//                    }
//                ]
//            }',
//            $visitor->getResponse()->getContent()
//        );
    }
}
