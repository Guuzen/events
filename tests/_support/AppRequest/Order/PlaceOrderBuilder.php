<?php

namespace App\Tests\AppRequest\Order;

final class PlaceOrderBuilder
{
    private $tariffId;

    private $firstName;

    private $lastName;

    private $email;

    private $paymentMethod;

    private $phone;

    private function __construct(
        ?string $tariffId,
        string $firstName,
        string $lastName,
        string $email,
        string $paymentMethod,
        string $phone
    ) {
        $this->tariffId      = $tariffId;
        $this->firstName     = $firstName;
        $this->lastName      = $lastName;
        $this->email         = $email;
        $this->paymentMethod = $paymentMethod;
        $this->phone         = $phone;
    }

    public static function any(): self
    {
        return new self(null, 'john', 'Doe', 'john@email.com', 'wire', '+123456789');
    }

    public function build(): PlaceOrder
    {
        return new PlaceOrder(
            $this->tariffId,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->paymentMethod,
            $this->phone
        );
    }

    public function withTariffId(string $tariffId): self
    {
        return new self(
            $tariffId,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->paymentMethod,
            $this->phone
        );
    }

    public function withFirstName(string $firstName): self
    {
        return new self(
            $this->tariffId,
            $firstName,
            $this->lastName,
            $this->email,
            $this->paymentMethod,
            $this->phone
        );
    }

    public function withLastName(string $lastName): self
    {
        return new self(
            $this->tariffId,
            $this->firstName,
            $lastName,
            $this->email,
            $this->paymentMethod,
            $this->phone
        );
    }

    public function withEmail(string $email): self
    {
        return new self(
            $this->tariffId,
            $this->firstName,
            $this->lastName,
            $email,
            $this->paymentMethod,
            $this->phone
        );
    }

    public function withPaymentMethod(string $paymentMethod): self
    {
        return new self(
            $this->tariffId,
            $this->firstName,
            $this->lastName,
            $this->email,
            $paymentMethod,
            $this->phone
        );
    }

    public function withPhone(string $phone): self
    {
        return new self(
            $this->tariffId,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->paymentMethod,
            $phone
        );
    }
}
