<?php

namespace App\Model\Tariff;

use App\Model\Event\EventId;
use App\Model\Order\Order;
use App\Model\Order\OrderId;
use App\Model\User\UserId;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Money\Money;

/**
 * @ORM\Entity
 */
class Tariff
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type=TariffId::class)
     */
    private $id;

    /**
     * @ORM\Column(type=EventId::class)
     */
    private $eventId;

    /**
     * @ORM\Column(type=TariffPriceNet::class)
     */
    private $priceNet;

    /**
     * @ORM\Column(type=ProductType::class)
     */
    private $productType;

    public function __construct(TariffId $id, EventId $eventId, TariffPriceNet $priceNet, ProductType $productType)
    {
        $this->id          = $id;
        $this->eventId     = $eventId;
        $this->priceNet    = $priceNet;
        $this->productType = $productType;
    }

    public function calculatePrice(DateTimeImmutable $asOf): Money
    {
        return $this->priceNet->calculatePrice($asOf);
    }

    public function makeOrder(
        OrderId $orderId,
        EventId $eventId,
        UserId $userId,
        Money $price,
        DateTimeImmutable $asOf
    ): Order
    {
        return new Order($orderId, $eventId, $this->productType, $this->id, $userId, $price, $asOf);
    }
}
