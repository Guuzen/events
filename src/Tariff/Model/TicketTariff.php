<?php

namespace App\Tariff\Model;

use App\Event\Model\EventId;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Product\Model\Product;
use App\Product\Model\ProductId;
use App\Order\Model\TariffId;
use App\Product\Model\Products;
use App\Product\Model\ProductType;
use App\Product\Model\Ticket;
use App\Product\Model\TicketId;
use App\Promocode\Model\AllowedTariffs\AllowedTariffs;
use App\Promocode\Model\Discount\Discount;
use App\Promocode\Model\Exception\CantCalculateSum;
use App\Promocode\Model\Promocode;
use App\Tariff\Model\Exception\OrderTariffMustBeRelatedToEvent;
use App\User\Model\User;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Money\Money;

/**
 * @ORM\Entity
 */
class TicketTariff implements Tariff
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="app_ticket_tariff_id")
     */
    private $id;

    /**
     * @ORM\Column(type="app_event_id")
     */
    private $eventId;

    /**
     * @var TariffPriceNet
     * @ORM\Column(type="json_document")
     */
    private $priceNet;

    /**
     * @var ProductType
     * @ORM\Column(type="json_document")
     */
    private $productType;

    public function __construct(TicketTariffId $id, EventId $eventId, TariffPriceNet $priceNet, ProductType $productType)
    {
        $this->id          = $id;
        $this->eventId     = $eventId;
        $this->priceNet    = $priceNet;
        $this->productType = $productType;
    }

    public function calculateSum(Discount $discount, DateTimeImmutable $asOf): ?Money
    {
        return $this->priceNet->calculateSum($discount, $asOf);
    }

    public function relatedToEvent(EventId $eventId): bool
    {
        return $this->eventId->equals($eventId);
    }

    public function allowedForUse(AllowedTariffs $allowedTariffs): bool
    {
        return $allowedTariffs->contains(TariffId::fromString($this->id));
    }

    public function findNotReservedProduct(Products $products): ?Product
    {
        return $products->findNotReservedByType($this->productType);
    }

    public function createProduct(ProductId $productId)
    {
        return new Product($productId, $this->eventId, $this->productType);
    }

    public function makeOrder(
        OrderId $orderId,
        EventId $eventId,
        ProductId $productId,
        Promocode $promocode,
        User $user,
        DateTimeImmutable $asOf
    ): Order {
        if (!$this->eventId->equals($eventId)) {
            throw new OrderTariffMustBeRelatedToEvent();
        }
        // TODO может вытянуть сумму на дату, а скидку по промокоду в промокоде применить?
        $sum = $this->calculateSum($promocode, $asOf);
        if (null === $sum) {
            throw new CantCalculateSum(); // TODO where is this exception belongs ?
        }

        return $promocode->makeOrder($orderId, $eventId, $productId, $this->id, $user, $sum, $asOf);
    }

//    public function createActiveTariff(ActiveTariffType $tariffType): ActiveTariff
//    {
//        return new ActiveTariff($this->id, $tariffType);
//    }

//    // TODO можно ли вынести изменение стейтов промокода и тарифа в command handler ?
//    public function createInvoice(InvoiceId $invoiceId, DateTime $asOf, Promocode $promocode): Invoice
//    {
//        if ($this->isCreatedInInvoice($invoiceId)) {
//            throw new TariffAlreadyCreatedInInvoice();
//        }
//
//        if (!$this->priceNet->match($asOf)) {
//            throw new TariffTermNotMatchInvoiceMakeAt();
//        }
//
//        $price = $this->priceNet->priceAsOf($asOf);
//        if (null === $price) {
//            throw new ThereIsMustBePriceInInvoice();
//        }
//        $sum = $price->calculateSum($promocode);
//
//
//        $promocode->use($invoiceId, $this, $asOf);
//
//        $this->createdInInvoices[] = new TariffInvoice($invoiceId, $asOf, $price);
//
//        return new Invoice($invoiceId, $sum, $asOf, $this->id, $promocode->getId());
//    }

//    public function disableInvoice(InvoiceId $invoiceId): void
//    {
//        if (!$this->isCreatedInInvoice($invoiceId)) {
//            throw new DisableNotExistsInvoiceIsNotPossible();
//        }
//
//        $this->createdInInvoices = array_filter($this->createdInInvoices, function (TariffInvoice $invoice) use ($invoiceId) {
//            return !$invoice->hasSameInvoiceIdAs($invoiceId);
//        });
//    }

//    public function changePriceNet(TariffPriceNet $priceNet): void
//    {
//        foreach ($this->createdInInvoices as $invoice) {
//            if (!$invoice->isCreatedAtMatchTerm($priceNet)) {
//                throw new NewTermNotMatchAlreadyCreatedInvoiceTerm();
//            }
//        }
//
//        $this->term = $priceNet;
//    }

//    private function isCreatedInInvoice(InvoiceId $invoiceId): bool
//    {
//        foreach ($this->createdInInvoices as $invoice) {
//            if ($invoice->hasSameInvoiceIdAs($invoiceId)) {
//                return true;
//            }
//        }
//
//        return false;
//    }
}
