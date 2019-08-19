<?php

namespace App\Tests\AppResponse\OrderInOrderList;

final class OrderInOrderList implements \JsonSerializable
{
    private $id;

    private $eventId;

    private $tariffId;

    private $productId;

    private $paid;

    private $product;

    private $phone;

    private $firstName;

    private $lastName;

    private $email;

    private $sum;

    private $currency;

    private $cancelled;

    public function __construct(
        string $id,
        string $eventId,
        string $tariffId,
        string $productId,
        bool $paid,
        string $product,
        string $phone,
        string $firstName,
        string $lastName,
        string $email,
        string $sum,
        string $currency,
        bool $cancelled
    ) {
        $this->id        = $id;
        $this->eventId   = $eventId;
        $this->tariffId  = $tariffId;
        $this->productId = $productId;
        $this->paid      = $paid;
        $this->product   = $product;
        $this->phone     = $phone;
        $this->firstName = $firstName;
        $this->lastName  = $lastName;
        $this->email     = $email;
        $this->sum       = $sum;
        $this->currency  = $currency;
        $this->cancelled = $cancelled;
    }

    public function jsonSerialize(): array
    {
        return [
            'id'         => $this->id,
            'event_id'   => $this->eventId,
            'tariff_id'  => $this->tariffId,
            'product_id' => $this->productId,
            'paid'       => $this->paid,
            'product'    => $this->product,
            'phone'      => $this->phone,
            'first_name' => $this->firstName,
            'last_name'  => $this->lastName,
            'email'      => $this->email,
            'sum'        => $this->sum,
            'currency'   => $this->currency,
            'cancelled'  => $this->cancelled,
        ];
    }
}
