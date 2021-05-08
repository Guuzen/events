<?php

declare(strict_types=1);

namespace App\Model\TicketOrder;

use App\Infrastructure\DomainEvent\Entity;
use App\Integrations\Fondy\FondyClient;
use App\Model\Event\EventId;
use App\Model\Promocode\Promocode;
use App\Model\Promocode\PromocodeId;
use App\Model\Tariff\TariffId;
use App\Model\User\UserId;
use Doctrine\ORM\Mapping as ORM;
use Money\Money;

/**
 * @ORM\Entity
 */
final class TicketOrder extends Entity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type=TicketOrderId::class)
     */
    private $id;

    /**
     * @ORM\Column(type=TariffId::class, nullable=false)
     */
    private $tariffId;

    /**
     * @ORM\Column(type=EventId::class)
     */
    private $eventId;

    /**
     * @ORM\Column(type=UserId::class)
     */
    private $userId;

    /**
     * @var Money
     *
     * @ORM\Column(type=Money::class)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $tariffType;

    /**
     * @var \DateTimeImmutable
     *
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $makedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $paid; // TODO primitive obsession ?

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

    /**
     * @var Money
     *
     * @ORM\Column(type=Money::class, nullable=false)
     */
    private $total;

    public function __construct(
        TicketOrderId $id,
        TariffId $tariffId,
        EventId $eventId,
        UserId $userId,
        Money $price,
        string $tariffType,
        \DateTimeImmutable $makedAt,
        bool $paid = false
    )
    {
        $this->id         = $id;
        $this->tariffId   = $tariffId;
        $this->eventId    = $eventId;
        $this->userId     = $userId;
        $this->price      = $price;
        $this->total      = $price;
        $this->tariffType = $tariffType;
        $this->makedAt    = $makedAt;
        $this->paid       = $paid;
    }

    public function confirmPayment(): void
    {
        $this->paid = true;

        $this->rememberThat(
            new TicketOrderPaymentConfirmed($this->eventId, $this->id, $this->userId, $this->promocodeId)
        );
    }

    public function createFondyPayment(FondyClient $fondy): string
    {
        return $fondy->checkoutUrl($this->price, (string)$this->id);
    }

    public function applyPromocode(Promocode $promocode): void
    {
        $this->promocodeId = $promocode->id;
        $this->total       = $promocode->applyTo($this->price, $this->tariffId, $this->makedAt);
    }
}