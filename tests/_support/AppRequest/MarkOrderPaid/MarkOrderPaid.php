<?php

namespace App\Tests\AppRequest\MarkOrderPaid;

final class MarkOrderPaid implements \JsonSerializable
{
    private $orderId;

    public function __construct(string $orderId)
    {
        $this->orderId = $orderId;
    }

    public function jsonSerialize(): array
    {
        return [
            'order_id' => $this->orderId,
        ];
    }
}
