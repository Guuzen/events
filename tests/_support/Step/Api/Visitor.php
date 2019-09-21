<?php

namespace App\Tests\Step\Api;

use App\Tests\Contract\AppRequest\Order\PlaceOrder;

class Visitor extends \App\Tests\ApiTester
{
    public function placeOrder(PlaceOrder $placeOrder): string
    {
        $I = $this;

        $I->haveHttpHeader('HOST', '2019foo.event.com');
        $I->sendPOST('/order/place', $placeOrder);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsId();

        return $I->grabIdFromResponse();
    }
}
