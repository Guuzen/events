<?php
declare(strict_types=1);

namespace App\Tariff\Model;

use App\Event\Model\ActiveTariff;
use App\Event\Model\ActiveTariffType;
use App\Event\Model\EventId;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Product\Model\Product;
use App\Promocode\Model\AllowedTariffs\AllowedTariffs;
use App\Promocode\Model\Discount\Discount;
use App\Promocode\Model\PromocodeId;
use App\Tariff\Model\Exception\OrderTariffMustBeRelatedWithEvent;
use App\User\Model\User;
use DateTimeImmutable;
use Money\Money;

class Tariff
{
    private $id;

    private $eventId;

    private $priceNet;

    private $productType;

    public function __construct(TariffId $id, EventId $eventId, TariffPriceNet $priceNet, ProductType $productType)
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
        return $allowedTariffs->contains($this->id);
    }

    public function makeOrder(
        OrderId $orderId,
        EventId $eventId,
        ?PromocodeId $promocodeId,
        Product $product,
        User $user,
        Money $sum,
        DateTimeImmutable $makedAt
    ): Order
    {
        if (!$this->eventId->equals($eventId)) {
            throw new OrderTariffMustBeRelatedWithEvent();
        }

        return $product->makeOrder($orderId, $eventId, $this->id, $promocodeId, $user, $sum, $makedAt);
    }

    public function createActiveTariff(ActiveTariffType $tariffType): ActiveTariff
    {
        return new ActiveTariff($this->id, $tariffType);
    }


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
