<?php

namespace App\Order\Model;

use App\Cloudpayments\Payment;
use App\Cloudpayments\PaymentStatus;
use App\Event\Model\EventId;
use App\Order\Model\Exception\OrderAlreadyPaid;
use App\Order\Model\Exception\OrderCancelled;
use App\Product\Model\ProductId;
use App\Promocode\Model\Promocode;
use App\Tariff\Model\TariffId;
use App\User\Model\UserId;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Money\Money;

/**
 * @ORM\Entity
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="app_order_id")
     */
    private $id;

    /**
     * @ORM\Column(type="app_event_id")
     */
    private $eventId;

    /**
     * @ORM\Column(type="app_product_id")
     */
    private $productId;

    /**
     * TODO remove ? What is it for?
     *
     * @ORM\Column(type="app_tariff_id")
     */
    private $tariffId;

    /**
     * @ORM\Column(type="app_promocode_id", nullable=true)
     */
    private $promocodeId;

    /**
     * @ORM\Column(type="app_user_id")
     */
    private $userId;

    /**
     * @ORM\Column(type="app_money")
     */
    private $sum;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $makedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $paid;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cancelled = false;

    public function __construct(
        OrderId $id,
        EventId $eventId,
        ProductId $productId,
        TariffId $tariffId,
        UserId $userId,
        Money $sum,
        DateTimeImmutable $asOf,
        bool $paid = false
    ) {
        $this->id          = $id;
        $this->eventId     = $eventId;
        $this->productId   = $productId;
        $this->tariffId    = $tariffId;
        $this->userId      = $userId;
        $this->sum         = $sum;
        $this->makedAt     = $asOf;
        $this->paid        = $paid;
    }

    public function applyPromocode(Promocode $promocode): void
    {
        $this->sum = $promocode->apply($this->sum);
    }

    public function createCloudpaymentsPayment(
        string $transactionId,
        PaymentStatus $status
    ): Payment {
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
