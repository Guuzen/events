<?php

namespace App\Tests\Contract\AppRequest\Order;

final class PlaceOrder
{
    private $tariffId;

    private $firstName;

    private $lastName;

    private $email;

    private $phone;

    private function __construct(
        string $tariffId,
        ?string $promocode,
        string $firstName,
        string $lastName,
        string $email,
        string $phone
    ) {
        $this->tariffId      = $tariffId;
        $this->firstName     = $firstName;
        $this->lastName      = $lastName;
        $this->email         = $email;
        $this->phone         = $phone;
    }

    public static function anyWith(string $tariffId, ?string $promocode): self
    {
        return new self($tariffId, $promocode, 'john', 'Doe', 'john@email.com', '+123456789');
    }
}
