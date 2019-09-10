<?php

namespace App\Queries\Order\FindOrderById;

use DateTimeImmutable;

/**
 * @psalm-immutable
 */
final class OrderById
{
    public $id;

    public $eventId;

    public $tariffId;

    public $productId;

    public $userId;

    public $paid;

    public $product;

    public $phone;

    public $firstName;

    public $lastName;

    public $email;

    public $sum;

    public $currency;

    public $cancelled;

    public $makedAt;

    public $deliveredAt;

    private function __construct(
        string $id,
        string $eventId,
        string $tariffId,
        string $productId,
        string $userId,
        bool $paid,
        string $product,
        string $phone,
        string $firstName,
        string $lastName,
        string $email,
        string $sum,
        string $currency,
        bool $cancelled,
        DateTimeImmutable $makedAt,
        ?DateTimeImmutable $deliveredAt
    ) {
        $this->id          = $id;
        $this->eventId     = $eventId;
        $this->tariffId    = $tariffId;
        $this->productId   = $productId;
        $this->userId      = $userId;
        $this->paid        = $paid;
        $this->product     = $product;
        $this->phone       = $phone;
        $this->firstName   = $firstName;
        $this->lastName    = $lastName;
        $this->email       = $email;
        $this->sum         = $sum;
        $this->currency    = $currency;
        $this->cancelled   = $cancelled;
        $this->makedAt     = $makedAt;
        $this->deliveredAt = $deliveredAt;
    }
}
