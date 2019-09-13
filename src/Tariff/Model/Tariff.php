<?php

namespace App\Tariff\Model;

use App\Event\Model\EventId;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Product\Model\Error\OrderAndProductMustBeRelatedToSameEvent;
use App\Product\Model\Error\OrderAndProductMustBeRelatedToSameTariff;
use App\Product\Model\Product;
use App\Product\Model\ProductId;
use App\Promocode\Model\AllowedTariffs\AllowedTariffs;
use App\Promocode\Model\Discount\Discount;
use App\Tariff\Model\Error\TariffAndOrderMustBeRelatedToSameEvent;
use App\Tariff\Model\Error\TariffSegmentNotFound;
use App\User\Model\User;
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
     * @ORM\Column(type="app_tariff_price_net")
     */
    private $priceNet;

    public function __construct(TariffId $id, EventId $eventId, TariffPriceNet $priceNet)
    {
        $this->id          = $id;
        $this->eventId     = $eventId;
        $this->priceNet    = $priceNet;
    }

    /**
     * @return Money|TariffSegmentNotFound
     */
    public function calculateSum(Discount $discount, DateTimeImmutable $asOf)
    {
        return $this->priceNet->calculateSum($discount, $asOf);
    }

    public function relatedToEvent(EventId $eventId): bool
    {
        return $this->eventId->equals($eventId);
    }

    public function allowedForUse(AllowedTariffs $allowedTariffs): bool
    {
        return $allowedTariffs->contains(new TariffId((string) $this->id));
    }

    public function createProduct(ProductId $productId, DateTimeImmutable $createdAt): Product
    {
        return new Product($productId, $this->eventId, $this->id, $createdAt);
    }

    /**
     * @return Order|TariffAndOrderMustBeRelatedToSameEvent|OrderAndProductMustBeRelatedToSameEvent|OrderAndProductMustBeRelatedToSameTariff
     */
    public function makeOrder(
        OrderId $orderId,
        EventId $eventId,
        Product $product,
        Money $sum,
        User $user,
        DateTimeImmutable $asOf
    ) {
        if (!$this->eventId->equals($eventId)) {
            return new TariffAndOrderMustBeRelatedToSameEvent();
        }

        return $product->makeOrder($orderId, $eventId, $this->id, $sum, $user, $asOf);
    }
}
