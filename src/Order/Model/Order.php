<?php

namespace App\Order\Model;

use App\Common\Error;
use App\Event\Model\EventId;
use App\Order\Model\Error\OrderAlreadyPaid;
use App\Order\Model\Exception\OrderCancelled;
use App\Product\Model\ProductId;
use App\Product\Model\Products;
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

    public function cancel(): void
    {
        if ($this->cancelled) {
            throw new OrderCancelled();
        }

        $this->cancelled = true;
        // TODO raise InvoiceCancelled for cancel Promocode
    }

    // TODO rename pay ?
    public function markPaid(): ?Error
    {
        if ($this->paid) {
            return new OrderAlreadyPaid();
        }

        $this->paid = true;

        return null;
    }

    public function findProductById(Products $products)
    {
        return $products->findById($this->productId);
    }
}
