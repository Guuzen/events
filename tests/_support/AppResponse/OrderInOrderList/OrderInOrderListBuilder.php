<?php

namespace App\Tests\AppResponse\OrderInOrderList;

final class OrderInOrderListBuilder
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

    private function __construct(
        ?string $id,
        ?string $eventId,
        ?string $tariffId,
        ?string $productId,
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

    public static function any(): self
    {
        return new self(
            null,
            null,
            null,
            null,
            false,
            'silver_pass',
            '+123456789',
            'john',
            'Doe',
            'john@email.com',
            '200',
            'RUB',
            false
        );
    }

    public function build(): OrderInOrderList
    {
        return new OrderInOrderList(
            $this->id,
            $this->eventId,
            $this->tariffId,
            $this->productId,
            $this->paid,
            $this->product,
            $this->phone,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->sum,
            $this->currency,
            $this->cancelled
        );
    }

    public function withId(?string $id): self
    {
        return new self(
            $id,
            $this->eventId,
            $this->tariffId,
            $this->productId,
            $this->paid,
            $this->product,
            $this->phone,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->sum,
            $this->currency,
            $this->cancelled
        );
    }

    public function withEventId(?string $eventId): self
    {
        return new self(
            $this->id,
            $eventId,
            $this->tariffId,
            $this->productId,
            $this->paid,
            $this->product,
            $this->phone,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->sum,
            $this->currency,
            $this->cancelled
        );
    }

    public function withTariffId(?string $tariffId): self
    {
        return new self(
            $this->id,
            $this->eventId,
            $tariffId,
            $this->productId,
            $this->paid,
            $this->product,
            $this->phone,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->sum,
            $this->currency,
            $this->cancelled
        );
    }

    public function withProductId(?string $productId): self
    {
        return new self(
            $this->id,
            $this->eventId,
            $this->tariffId,
            $productId,
            $this->paid,
            $this->product,
            $this->phone,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->sum,
            $this->currency,
            $this->cancelled
        );
    }

    public function withPaid(bool $paid): self
    {
        return new self(
            $this->id,
            $this->eventId,
            $this->tariffId,
            $this->productId,
            $paid,
            $this->product,
            $this->phone,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->sum,
            $this->currency,
            $this->cancelled
        );
    }

    public function withProduct(string $product): self
    {
        return new self(
            $this->id,
            $this->eventId,
            $this->tariffId,
            $this->productId,
            $this->paid,
            $product,
            $this->phone,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->sum,
            $this->currency,
            $this->cancelled
        );
    }

    public function withPhone(string $phone): self
    {
        return new self(
            $this->id,
            $this->eventId,
            $this->tariffId,
            $this->productId,
            $this->paid,
            $this->product,
            $phone,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->sum,
            $this->currency,
            $this->cancelled
        );
    }

    public function withFirstName(string $firstName): self
    {
        return new self(
            $this->id,
            $this->eventId,
            $this->tariffId,
            $this->productId,
            $this->paid,
            $this->product,
            $this->phone,
            $firstName,
            $this->lastName,
            $this->email,
            $this->sum,
            $this->currency,
            $this->cancelled
        );
    }

    public function withLastName(string $lastName): self
    {
        return new self(
            $this->id,
            $this->eventId,
            $this->tariffId,
            $this->productId,
            $this->paid,
            $this->product,
            $this->phone,
            $this->firstName,
            $lastName,
            $this->email,
            $this->sum,
            $this->currency,
            $this->cancelled
        );
    }

    public function withEmail(string $email): self
    {
        return new self(
            $this->id,
            $this->eventId,
            $this->tariffId,
            $this->productId,
            $this->paid,
            $this->product,
            $this->phone,
            $this->firstName,
            $this->lastName,
            $email,
            $this->sum,
            $this->currency,
            $this->cancelled
        );
    }

    public function withSum(string $sum): self
    {
        return new self(
            $this->id,
            $this->eventId,
            $this->tariffId,
            $this->productId,
            $this->paid,
            $this->product,
            $this->phone,
            $this->firstName,
            $this->lastName,
            $this->email,
            $sum,
            $this->currency,
            $this->cancelled
        );
    }

    public function withCurrency(string $currency): self
    {
        return new self(
            $this->id,
            $this->eventId,
            $this->tariffId,
            $this->productId,
            $this->paid,
            $this->product,
            $this->phone,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->sum,
            $currency,
            $this->cancelled
        );
    }

    public function withCancelled(bool $cancelled): self
    {
        return new self(
            $this->id,
            $this->eventId,
            $this->tariffId,
            $this->productId,
            $this->paid,
            $this->product,
            $this->phone,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->sum,
            $this->currency,
            $cancelled
        );
    }
}
