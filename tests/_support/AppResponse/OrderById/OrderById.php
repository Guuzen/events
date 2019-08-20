<?php

namespace App\Tests\AppResponse\OrderById;

final class OrderById
{
    private $id;

    private $eventId;

    private $tariffId;

    private $productId;

    private $userId;

    private $paid;

    private $product;

    private $phone;

    private $firstName;

    private $lastName;

    private $email;

    private $sum;

    private $currency;

    private $cancelled;

    private $makedAt;

    private $deliveredAt;

    public function __construct(
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
        string $makedAt,
        ?string $deliveredAt
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
