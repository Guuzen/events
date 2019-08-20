<?php

namespace App\Tests\AppRequest\Order;

final class MarkOrderPaidBuilder
{
    private $orderId;

    public function __construct(?string $orderId)
    {
        $this->orderId = $orderId;
    }

    public function build(): MarkOrderPaid
    {
        return new MarkOrderPaid($this->orderId);
    }

    public static function any(): self
    {
        return new self(null);
    }

    public function withOrderId(string $orderId): self
    {
        return new self($orderId);
    }
}
