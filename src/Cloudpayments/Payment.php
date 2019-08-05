<?php

namespace App\Cloudpayments;

use App\Order\Model\OrderId;
use Money\Money;

final class Payment
{
    private $transactionId;

    private $invoiceId;

    private $amount;

    private $status;

    public function __construct(string $transactionId, OrderId $invoiceId, Money $amount, PaymentStatus $status)
    {
        $this->transactionId = $transactionId;
        $this->invoiceId     = $invoiceId;
        $this->amount        = $amount;
        $this->status        = $status;
    }
}
