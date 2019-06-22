<?php
declare(strict_types=1);

namespace App\EventFoo\PaymentByBankCard;

use App\EventFoo\Invoice\Model\Invoice;
use App\EventFoo\Visitor;
use Cardinity\Client;
use Cardinity\Method\Payment\Create;

final class CardinityPayment implements Payment
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function pay(Visitor $visitor, Invoice $invoice, BankCard $bankCard): void
    {
        $amount = $invoice->getSum();
        $payment = new Create([
            'amount'             => $amount->getAmount(),
            'currency'           => $amount->getCurrency(),
            'settle'             => true,
            'description'        => $invoice->description(),
            'order_id'           => $invoice->getHumanId(),
//            'country'            => $visitor->country,
            'payment_method'     => Create::CARD,
            'payment_instrument' => [
                'pan'       => $bankCard->getPan(),
                'exp_year'  => $bankCard->getExpYear(),
                'exp_month' => $bankCard->getExpMonth(),
                'cvc'       => $bankCard->getCvc(),
                'holder'    => $bankCard->getHolder(),
            ],
        ]);

        $this->client->call($payment);
    }
}
