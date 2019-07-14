<?php

namespace App\Event\Model;

use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Order\Model\OrderIds;
use App\Order\Model\TariffId;
use App\Product\Model\Product;
use App\Promocode\Model\AllowedTariffs\EventAllowedTariffs;
use App\Promocode\Model\AllowedTariffs\SpecificAllowedTariffs;
use App\Promocode\Model\Discount\Discount;
use App\Promocode\Model\Promocode;
use App\Promocode\Model\PromocodeId;
use App\Promocode\Model\RegularPromocode;
use App\Tariff\Model\Tariff;
use App\Tariff\Model\TariffIds;
use App\Tariff\Model\TariffPriceNet;
use App\Tariff\Model\TicketTariff;
use App\Tariff\Model\TicketTariffId;
use App\User\Model\User;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
final class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="app_event_id")
     */
    private $id;

    /**
     * @ORM\Column(type="app_tariff_ids")
     * @var TariffIds
     */
    private $tariffIds;

    /**
     * @ORM\Column(type="app_order_ids")
     * @var OrderIds
     */
    private $orderIds;

    public function __construct(EventId $id, TariffIds $tariffIds, OrderIds $orderIds)
    {
        $this->id        = $id;
        $this->tariffIds = $tariffIds;
        $this->orderIds  = $orderIds;
    }

    public function makeOrder(
        OrderId $orderId,
        Product $product,
        Tariff $tariff,
        Promocode $promocode,
        User $user,
        DateTimeImmutable $asOf
    ): Order {
        // TODO check unique ?
        $this->orderIds->push($orderId);

        return $product->makeOrder($orderId, $this->id, $tariff, $promocode, $user, $asOf);
    }

    public function createTicketTariff(
        TicketTariffId $ticketTariffId,
        TariffPriceNet $tariffPriceNet,
        string $status
    ): TicketTariff {
        // TODO check unique ?
        $this->tariffIds->push(TariffId::fromString($ticketTariffId));

        return new TicketTariff($ticketTariffId, $this->id, $tariffPriceNet, $status);
    }

    public function createEventPromocode(
        PromocodeId $promocodeId,
        Discount $discount,
        int $useLimit,
        DateTimeImmutable $expireAt
    ): RegularPromocode {
        return new RegularPromocode(
            $promocodeId,
            $this->id,
            $discount,
            $useLimit,
            $expireAt,
            new EventAllowedTariffs()
        );
    }

    public function createRegularPromocode(
        PromocodeId $promocodeId,
        Discount $discount,
        int $useLimit,
        DateTimeImmutable $expireAt,
        array $allowedTariffIds,
        array $usedInOrders
    ): RegularPromocode {
        if (!$this->tariffIds->containsAllOf(new TariffIds($allowedTariffIds))) {
            throw new \Exception('allowed tariffs is not in event tariffs');
        }
        if (!$this->orderIds->containsAllOf(new OrderIds($usedInOrders))) {
            throw new \Exception('used in orders which not in event order ids');
        }

        return new RegularPromocode(
            $promocodeId,
            $this->id,
            $discount,
            $useLimit,
            $expireAt,
            new SpecificAllowedTariffs($allowedTariffIds),
            $usedInOrders
        );
    }
}
