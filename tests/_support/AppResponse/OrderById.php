<?php

namespace App\Tests\AppResponse;

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

    public static function anySilverNotPaidNotDeliveredWith(string $orderId, string $eventId, string $tariffId, string $ticketId): self
    {
        return new self(
            $orderId,
            $eventId,
            $tariffId,
            $ticketId,
            '@uuid@',
            false,
            'silver_pass',
            '+123456789',
            'john',
            'Doe',
            'john@email.com',
            '200',
            'RUB',
            false,
            '@string@.isDateTime()',
            null
        );
    }

    public static function anySilverPaidDeliveredWith(string $orderId, string $eventId, string $tariffId, string $ticketId): self
    {
        return new self(
            $orderId,
            $eventId,
            $tariffId,
            $ticketId,
            '@uuid@',
            true,
            'silver_pass',
            '+123456789',
            'john',
            'Doe',
            'john@email.com',
            '200',
            'RUB',
            false,
            '@string@.isDateTime()',
            '@string@.isDateTime()'
        );
    }
}
