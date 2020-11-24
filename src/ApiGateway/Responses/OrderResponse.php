<?php

declare(strict_types=1);

namespace App\ApiGateway\Responses;

use App\Infrastructure\ResponseComposer\Schema;
use App\Infrastructure\ResponseComposer\SchemaProvider;
use App\Order\Query\OrderResource;
use App\Promocode\Model\Discount\Discount;
use App\Promocode\Query\PromocodeResource;
use Money\Money;

final class OrderResponse implements SchemaProvider
{
    private OrderResource $order;

    private ?PromocodeResponse $promocode;

    public function __construct(OrderResource $order, ?PromocodeResponse $promocode)
    {
        $this->order     = $order;
        $this->promocode = $promocode;
    }

    public static function schema(): Schema
    {
        $schema = new Schema(self::class);
        $schema->oneToOne(
            PromocodeResponse::schema(),
            fn(OrderResource $order) => $order->promocodeId,
            fn(PromocodeResource $promocode) => $promocode->id
        );

        return $schema;
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

        return $this->promocode->getDiscount()->apply($this->order->price);
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

        return $this->promocode->getDiscount();
    }
}
