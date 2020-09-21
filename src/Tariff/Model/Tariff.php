<?php

namespace App\Tariff\Model;

use App\Event\Model\EventId;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Promocode\Model\Discount\Discount;
use App\User\Model\UserId;
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

    public function calculateSum(Discount $discount, DateTimeImmutable $asOf): Money // TODO remove discount ?
    {
        return $this->priceNet->calculateSum($discount, $asOf);
    }

    public function makeOrder(
        OrderId $orderId,
        EventId $eventId,
        UserId $userId,
        Money $sum,
        DateTimeImmutable $asOf
    ): Order
    {
        return new Order($orderId, $eventId, $this->productType, $this->id, $userId, $sum, $asOf);
    }
}
