<?php

namespace App\Tests\Step\Api;

use App\Tests\ApiTester;
use App\Tests\Contract\AppRequest\Order\MarkPaidByFondy;

final class Fondy extends ApiTester
{
    public function orderPaid(MarkPaidByFondy $markPaidByFondy): void
    {
        $I = $this;
//        $I->insulate();

        $I->sendPOST('/order/markPaidByFondy', $markPaidByFondy);

        $I->seeResponseCodeIsSuccessful();
    }
}
