<?php

namespace App\Tests\AppRequest\Order;

final class PlaceOrder implements \JsonSerializable
{
    private $tariffId;

    private $firstName;

    private $lastName;

    private $email;

    private $paymentMethod;

    private $phone;

    public function __construct(
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

    public function jsonSerialize(): array
    {
        return [
            'first_name'     => $this->firstName,
            'last_name'      => $this->lastName,
            'email'          => $this->email,
            'payment_method' => $this->paymentMethod,
            'tariff_id'      => $this->tariffId,
            'phone'          => $this->phone,
        ];
    }
}
