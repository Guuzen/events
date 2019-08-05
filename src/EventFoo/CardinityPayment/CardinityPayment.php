<?php


namespace App\EventFoo\CardinityPayment;

use App\EventFoo\Invoice\Model\Invoice;
use App\EventFoo\Promocode\Promocode;
use App\EventFoo\Tariff\Tariff;
use App\User\Model\User;
use Cardinity\Client;
use Cardinity\Method\Payment\Create;

final class CardinityPayment
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function byCard(Invoice $invoice, Card $card): void
    {
//        $amount = $tariff->applyPromocode($promocode);

//        $payment = new Create([
//            'amount'             => $amount->getAmount(),
//            'currency'           => $amount->getCurrency(),
//            'settle'             => true,
//            'description'        => 'TODO order description',
//            'order_id'           => $r->get('invoice_id'),
//            'country'            => $user->getCountry(),
//            'payment_method'     => Create::CARD,
//            'payment_instrument' => [
//                'pan'       => $card->getPan(),
//                'exp_year'  => $card->getExpYear(),
//                'exp_month' => $card->getExpMonth(),
//                'cvc'       => $card->getCvc(),
//                'holder'    => $card->getHolder(),
//            ],
//        ]);
//
//        $this->client->call($payment);
    }
}
