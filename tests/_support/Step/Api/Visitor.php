<?php

namespace App\Tests\Step\Api;

class Visitor extends \App\Tests\ApiTester
{
    public function placeOrder(string $tariffId): string
    {
        $I = $this;

        $I->haveHttpHeader('HOST', '2019foo.event.com');
        $I->sendPOST('/order/place', [
            'first_name'     => 'john',
            'last_name'      => 'Doe',
            'email'          => 'john@email.com',
            'payment_method' => 'wire',
            'tariff_id'      => $tariffId,
            'phone'          => '+123456789',
        ]);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsId();

        return $I->grabIdFromResponse();
    }
}
