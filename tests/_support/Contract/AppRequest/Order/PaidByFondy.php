<?php

namespace App\Tests\Contract\AppRequest\Order;

final class PaidByFondy
{
    private $orderId;

    private function __construct(string $orderId)
    {
        $this->orderId = $orderId;
    }

    public static function with(string $orderId): self
    {
        return new self($orderId);
    }
}
