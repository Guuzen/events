<?php

namespace App\Tests\AppRequest\Order;

final class PlaceOrder
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
}
