<?php

namespace App\Model\Order;

use App\Model\Event\EventId;
use App\Integrations\Fondy\FondyClient;
use App\Infrastructure\DomainEvent\Entity;
use App\Model\Order\Exception\NotPossibleToApplyPromocodeTwiceOnOrder;
use App\Model\Order\Exception\OrderAlreadyPaid;
use App\Model\Order\Exception\OrderCancelled;
use App\Model\Promocode\PromocodeId;
use App\Model\Tariff\ProductType;
use App\Model\Tariff\TariffId;
use App\Model\User\UserId;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Money\Money;

/**
 * @ORM\Entity
 * @ORM\Table(name="`order`")
 */
class Order extends Entity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type=OrderId::class)
     */
    private $id;

    /**
     * @ORM\Column(type=EventId::class)
     */
    private $eventId;

    /**
     * TODO remove ? What is it for?
     *
     * @ORM\Column(type=TariffId::class)
     */
    private $tariffId;

    /**
     * @ORM\Column(type=ProductType::class)
     */
    private $productType;

    /**
     * @ORM\Column(type=UserId::class)
     */
    private $userId;

    /**
     * TODO rename to price
     *
     * @var Money
     *
     * @ORM\Column(type=Money::class)
     */
    private $price;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $makedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $paid; // TODO primitive obsession

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $cancelled = false;

    /**
     * @var PromocodeId|null
     *
     * @ORM\Column(type=PromocodeId::class, nullable=true)
     */
    private $promocodeId;

    public function __construct(
        OrderId $id,
        EventId $eventId,
        ProductType $productType,
        TariffId $tariffId,
        UserId $userId,
        Money $price,
        DateTimeImmutable $asOf,
        bool $paid = false
    )
    {
        $this->id          = $id;
        $this->eventId     = $eventId;
        $this->productType = $productType;
        $this->tariffId    = $tariffId;
        $this->userId      = $userId;
        $this->price       = $price;
        $this->makedAt     = $asOf;
        $this->paid        = $paid;
    }

    public function cancel(): void
    {
        if ($this->cancelled) {
            throw new OrderCancelled('');
        }

        $this->cancelled = true;
        // TODO raise InvoiceCancelled for cancel Promocode
    }

    // TODO rename pay/confirm payment ?
    public function markPaid(): void
    {
        if ($this->paid) {
            throw new OrderAlreadyPaid('');
        }
        $this->paid = true;

        $this->rememberThat(new OrderMarkedPaid($this->eventId, $this->productType, $this->id));
    }

    public function createFondyPayment(FondyClient $fondy): string
    {
        return $fondy->checkoutUrl($this->price, (string)$this->id);
    }

    public function applyPromocode(PromocodeId $promocodeId): void
    {
        if ($this->promocodeId !== null) {
            throw new NotPossibleToApplyPromocodeTwiceOnOrder(''); // TODO compensate use of promocode when new promocode applying
        }

        $this->promocodeId = $promocodeId;
    }
}
