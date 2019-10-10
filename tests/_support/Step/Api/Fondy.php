<?php

namespace App\Tests\Step\Api;

use App\Tests\ApiTester;
use App\Tests\Contract\AppRequest\Order\PaidByFondy;

final class Fondy extends ApiTester
{
    public function orderPaid(PaidByFondy $paidByFondy): void
    {
        $I = $this;

        $I->sendPOST('/order/paidByFondy', $paidByFondy);

        $I->seeResponseCodeIsSuccessful();
    }
}
