<?php

namespace App\Tariff\Model;

use App\Common\Error;
use App\Event\Model\EventId;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Product\Model\ProductType;
use App\Promocode\Model\Discount\Discount;
use App\Tariff\Model\Error\TariffSegmentNotFound;
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
     * @ORM\Column(type="app_tariff_id")
     */
    private $id;

    /**
     * @ORM\Column(type="app_event_id")
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

    /**
     * @return Money|TariffSegmentNotFound
     */
    public function calculateSum(Discount $discount, DateTimeImmutable $asOf) // TODO remove discount ?
    {
        return $this->priceNet->calculateSum($discount, $asOf);
    }

    /**
     * @return Order|Error
     */
    public function makeOrder(
        OrderId $orderId,
        EventId $eventId,
        UserId $userId,
        Money $sum,
        DateTimeImmutable $asOf
    )
    {
        return new Order($orderId, $eventId, $this->productType, $this->id, $userId, $sum, $asOf);
    }
}
