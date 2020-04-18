<?php
namespace App\Tests;

use App\Tests\Contract\AppRequest\Order\PayOrderByCard;
use App\Tests\Contract\AppRequest\Order\PlaceOrder;

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
class Visitor extends \Codeception\Actor
{
    use _generated\VisitorActions;

    public function placeOrder(array $placeOrder): string
    {
        $I = $this;

        $I->haveHttpHeader('HOST', '2019foo.event.com'); // TODO move to nginx ?
        $I->sendPOST('/order/place', $placeOrder);

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsId();

        return $I->grabIdFromResponse();
    }

    public function payOrderByCard(PayOrderByCard $payOrderByCard): string
    {
        $I = $this;

        $I->sendPOST('/order/payByCard', $payOrderByCard);

        $I->seeResponseCodeIsSuccessful();
        $paymentLink = $I->grabDataFromResponseByJsonPath('$.data');
        $I->assertArrayHasKey(0, $paymentLink, 'payment link not found');

        return $paymentLink[0];
    }
}
