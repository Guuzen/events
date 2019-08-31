<?php

namespace App\Queries\Order\FindOrdersInList;

final class OrderInList
{
    /**
     * @readonly
     */
    public $id;

    /**
     * @readonly
     */
    public $eventId;

    /**
     * @readonly
     */
    public $tariffId;

    /**
     * @readonly
     */
    public $productId;

    /**
     * @readonly
     */
    public $userId;

    /**
     * @readonly
     */
    public $paid;

    /**
     * @readonly
     */
    public $product;

    /**
     * @readonly
     */
    public $phone;

    /**
     * @readonly
     */
    public $firstName;

    /**
     * @readonly
     */
    public $lastName;

    /**
     * @readonly
     */
    public $email;

    /**
     * @readonly
     */
    public $sum;

    /**
     * @readonly
     */
    public $currency;

    /**
     * @readonly
     */
    public $cancelled;

    /**
     * @readonly
     */
    public $makedAt;

    /**
     * @readonly
     */
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
