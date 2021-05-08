<?php

namespace App\Model\Tariff;

use App\Model\Event\EventId;
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
}
