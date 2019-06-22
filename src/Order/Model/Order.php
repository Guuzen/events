<?php

namespace App\Order\Model;

use App\Cloudpayments\Payment;
use App\Cloudpayments\PaymentStatus;
use App\Event\Model\EventId;
use App\Order\Model\Exception\OrderAlreadyPaid;
use App\Order\Model\Exception\OrderCancelled;
use App\Promocode\Model\PromocodeId;
use App\Tariff\Model\TariffId;
use App\User\Model\UserId;
use DateTimeImmutable;
use Money\Money;

class Order
{
    private $id;

    private $eventId;

    private $userId;

    private $tariffId;

    private $promocodeId;

    private $productId;

    private $sum;

    private $makedAt;

    private $paid;

    private $cancelled = false;

    public function __construct(
        OrderId $id,
        EventId $eventId,
        ?PromocodeId $promocodeId,
        TariffId $tariffId,
        ProductId $productId,
        UserId $userId,
        Money $sum,
        DateTimeImmutable $makedAt,
        bool $paid = false
    )
    {
        $this->id          = $id;
        $this->eventId     = $eventId;
        $this->userId      = $userId;
        $this->tariffId    = $tariffId;
        $this->promocodeId = $promocodeId;
        $this->productId   = $productId;
        $this->sum         = $sum;
        $this->makedAt     = $makedAt;
        $this->paid        = $paid;
    }

    public function createCloudpaymentsPayment(
        string $transactionId,
        PaymentStatus $status
    ): Payment
    {
        if ($this->cancelled) {
            throw new OrderCancelled();
        }

        if ($this->paid) {
            throw new OrderAlreadyPaid();
        }

        return new Payment($transactionId, $this->id, $this->sum, $status);
    }

    public function cancel(): void
    {
        if ($this->cancelled) {
            throw new OrderCancelled();
        }

        $this->cancelled = true;
        // TODO raise InvoiceCancelled for cancel Promocode
    }
}
