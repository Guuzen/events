<?php

namespace App\Tests\AppResponse\OrderById;

final class OrderByIdBuilder
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
        ?string $id,
        ?string $eventId,
        ?string $tariffId,
        ?string $productId,
        ?string $userId,
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

    public static function any(): self
    {
        return new self(
            null,
            null,
            null,
            null,
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

    public function build(): OrderById
    {
        return new OrderById(
            $this->id,
            $this->eventId,
            $this->tariffId,
            $this->productId,
            $this->userId,
            $this->paid,
            $this->product,
            $this->phone,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->sum,
            $this->currency,
            $this->cancelled,
            $this->makedAt,
            $this->deliveredAt
        );
    }

    public function withId(?string $id): self
    {
        return new self(
            $id,
            $this->eventId,
            $this->tariffId,
            $this->productId,
            $this->userId,
            $this->paid,
            $this->product,
            $this->phone,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->sum,
            $this->currency,
            $this->cancelled,
            $this->makedAt,
            $this->deliveredAt
        );
    }

    public function withEventId(?string $eventId): self
    {
        return new self(
            $this->id,
            $eventId,
            $this->tariffId,
            $this->productId,
            $this->userId,
            $this->paid,
            $this->product,
            $this->phone,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->sum,
            $this->currency,
            $this->cancelled,
            $this->makedAt,
            $this->deliveredAt
        );
    }

    public function withTariffId(?string $tariffId): self
    {
        return new self(
            $this->id,
            $this->eventId,
            $tariffId,
            $this->productId,
            $this->userId,
            $this->paid,
            $this->product,
            $this->phone,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->sum,
            $this->currency,
            $this->cancelled,
            $this->makedAt,
            $this->deliveredAt
        );
    }

    public function withProductId(?string $productId): self
    {
        return new self(
            $this->id,
            $this->eventId,
            $this->tariffId,
            $productId,
            $this->userId,
            $this->paid,
            $this->product,
            $this->phone,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->sum,
            $this->currency,
            $this->cancelled,
            $this->makedAt,
            $this->deliveredAt
        );
    }

    public function withPaid(bool $paid): self
    {
        return new self(
            $this->id,
            $this->eventId,
            $this->tariffId,
            $this->productId,
            $this->userId,
            $paid,
            $this->product,
            $this->phone,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->sum,
            $this->currency,
            $this->cancelled,
            $this->makedAt,
            $this->deliveredAt
        );
    }

    public function withProduct(string $product): self
    {
        return new self(
            $this->id,
            $this->eventId,
            $this->tariffId,
            $this->productId,
            $this->userId,
            $this->paid,
            $product,
            $this->phone,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->sum,
            $this->currency,
            $this->cancelled,
            $this->makedAt,
            $this->deliveredAt
        );
    }

    public function withPhone(string $phone): self
    {
        return new self(
            $this->id,
            $this->eventId,
            $this->tariffId,
            $this->productId,
            $this->userId,
            $this->paid,
            $this->product,
            $phone,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->sum,
            $this->currency,
            $this->cancelled,
            $this->makedAt,
            $this->deliveredAt
        );
    }

    public function withFirstName(string $firstName): self
    {
        return new self(
            $this->id,
            $this->eventId,
            $this->tariffId,
            $this->productId,
            $this->userId,
            $this->paid,
            $this->product,
            $this->phone,
            $firstName,
            $this->lastName,
            $this->email,
            $this->sum,
            $this->currency,
            $this->cancelled,
            $this->makedAt,
            $this->deliveredAt
        );
    }

    public function withLastName(string $lastName): self
    {
        return new self(
            $this->id,
            $this->eventId,
            $this->tariffId,
            $this->productId,
            $this->userId,
            $this->paid,
            $this->product,
            $this->phone,
            $this->firstName,
            $lastName,
            $this->email,
            $this->sum,
            $this->currency,
            $this->cancelled,
            $this->makedAt,
            $this->deliveredAt
        );
    }

    public function withEmail(string $email): self
    {
        return new self(
            $this->id,
            $this->eventId,
            $this->tariffId,
            $this->productId,
            $this->userId,
            $this->paid,
            $this->product,
            $this->phone,
            $this->firstName,
            $this->lastName,
            $email,
            $this->sum,
            $this->currency,
            $this->cancelled,
            $this->makedAt,
            $this->deliveredAt
        );
    }

    public function withSum(string $sum): self
    {
        return new self(
            $this->id,
            $this->eventId,
            $this->tariffId,
            $this->productId,
            $this->userId,
            $this->paid,
            $this->product,
            $this->phone,
            $this->firstName,
            $this->lastName,
            $this->email,
            $sum,
            $this->currency,
            $this->cancelled,
            $this->makedAt,
            $this->deliveredAt
        );
    }

    public function withCurrency(string $currency): self
    {
        return new self(
            $this->id,
            $this->eventId,
            $this->tariffId,
            $this->productId,
            $this->userId,
            $this->paid,
            $this->product,
            $this->phone,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->sum,
            $currency,
            $this->cancelled,
            $this->makedAt,
            $this->deliveredAt
        );
    }

    public function withCancelled(bool $cancelled): self
    {
        return new self(
            $this->id,
            $this->eventId,
            $this->tariffId,
            $this->productId,
            $this->userId,
            $this->paid,
            $this->product,
            $this->phone,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->sum,
            $this->currency,
            $cancelled,
            $this->makedAt,
            $this->deliveredAt
        );
    }

    public function withUserId(bool $userId): self
    {
        return new self(
            $this->id,
            $this->eventId,
            $this->tariffId,
            $this->productId,
            $userId,
            $this->paid,
            $this->product,
            $this->phone,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->sum,
            $this->currency,
            $this->cancelled,
            $this->makedAt,
            $this->deliveredAt
        );
    }

    public function withMakedAt(string $makedAt): self
    {
        return new self(
            $this->id,
            $this->eventId,
            $this->tariffId,
            $this->productId,
            $this->userId,
            $this->paid,
            $this->product,
            $this->phone,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->sum,
            $this->currency,
            $this->cancelled,
            $makedAt,
            $this->deliveredAt
        );
    }

    public function withDeliveredAt(string $deliveredAt): self
    {
        return new self(
            $this->id,
            $this->eventId,
            $this->tariffId,
            $this->productId,
            $this->userId,
            $this->paid,
            $this->product,
            $this->phone,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->sum,
            $this->currency,
            $this->cancelled,
            $this->makedAt,
            $this->deliveredAt
        );
    }
}
