<?php

namespace App\Tests\Step\Api;

use App\Tests\AppRequest\Order\Order;

class Visitor extends \App\Tests\ApiTester
{
    public function placeOrder(Order $order): string
    {
        $I = $this;

        $I->haveHttpHeader('HOST', '2019foo.event.com');
        $I->sendPOST('/order/place', $order);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsId();

        return $I->grabIdFromResponse();
    }
}
