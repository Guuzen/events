<?php

declare(strict_types=1);

namespace App\ApiGateway\GetOrderList;

use App\Order\Query\OrderResource;
use App\Promocode\Model\Discount\Discount;
use App\Promocode\Query\PromocodeResource;
use Money\Money;

final class GetOrderListItemResponse
{
    private $order;

    private $promocode;

    public function __construct(OrderResource $order, ?PromocodeResource $promocode)
    {
        $this->order     = $order;
        $this->promocode = $promocode;
    }


    public function getId(): string
    {
        return $this->order->id;
    }

    public function getEventId(): string
    {
        return $this->order->eventId;
    }

    public function getTariffId(): string
    {
        return $this->order->tariffId;
    }

    public function getProductType(): string
    {
        return $this->order->productType;
    }

    public function getUserId(): string
    {
        return $this->order->userId;
    }

    public function getPrice(): Money
    {
        return $this->order->price;
    }

    public function getTotal(): Money
    {
        if ($this->promocode === null) {
            return $this->order->price;
        }

        return $this->promocode->discount->apply($this->order->price);
    }

    public function getMakedAt(): \DateTimeImmutable
    {
        return $this->order->makedAt;
    }

    public function isPaid(): bool
    {
        return $this->order->paid;
    }

    public function isCancelled(): bool
    {
        return $this->order->cancelled;
    }

    public function getPromocodeId(): ?string
    {
        return $this->order->promocodeId;
    }

    public function getDiscount(): ?Discount
    {
        if ($this->promocode === null) {
            return null;
        }
        return $this->promocode->discount;
    }
}
