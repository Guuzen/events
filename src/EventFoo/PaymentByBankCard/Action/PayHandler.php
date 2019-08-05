<?php

namespace App\EventFoo\PaymentByBankCard\Action;

use App\EventFoo\Invoice\Invoices;
use App\EventFoo\PaymentByBankCard\BankCard;
use App\EventFoo\PaymentByBankCard\Payment;
use App\EventFoo\Visitor;
use Ramsey\Uuid\Uuid;

final class PayHandler
{
    private $invoices;

    private $payment;

    public function __construct(Invoices $invoices, Payment $payment)
    {
        $this->invoices = $invoices;
        $this->payment  = $payment;
    }

    public function handle(Pay $pay): void
    {
        $visitor = new Visitor(Uuid::uuid4(), $pay->lang, $pay->country, $pay->city, $pay->ip);

        // TODO from read side
        $invoice = $this->invoices->getById(Uuid::fromString($pay->invoiceId));

        $bankCard = new BankCard($pay->pan, $pay->expYear, $pay->expMonth, $pay->cvc, $pay->holder);

        $this->payment->pay($visitor, $invoice, $bankCard);
    }
}
