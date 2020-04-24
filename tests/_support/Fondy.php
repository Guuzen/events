<?php
namespace App\Tests;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
*/
class Fondy extends \Codeception\Actor
{
    use _generated\FondyActions;

    public function orderPaid(array $markPaidByFondy): void
    {
        $I = $this;

        $I->sendPOST('/order/markPaidByFondy', $markPaidByFondy);

        $I->seeResponseCodeIsSuccessful();
    }
}
