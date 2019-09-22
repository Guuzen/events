<?php

namespace App\Tests\Contract\AppRequest\Order;

final class PlaceOrder
{
    private $tariffId;

    private $firstName;

    private $lastName;

    private $email;

    private $paymentMethod;

    private $phone;

    private function __construct(
        string $tariffId,
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

    public static function withPaymentMethodWireWith(string $tariffId): self
    {
        return new self($tariffId, 'john', 'Doe', 'john@email.com', 'wire', '+123456789');
    }

    public static function withPaymentMethodCardWith(string $tariffId): self
    {
        return new self($tariffId, 'john', 'Doe', 'john@email.com', 'card', '+123456789');
    }
}
