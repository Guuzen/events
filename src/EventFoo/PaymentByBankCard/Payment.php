<?php


namespace App\EventFoo\PaymentByBankCard;

use App\EventFoo\Invoice\Model\Invoice;
use App\EventFoo\Visitor;

interface Payment
{

    public function pay(Visitor $visitor, Invoice $invoice, BankCard $bankCard): void;
}
