<?php

namespace App\Tests\AppRequest\MarkOrderPaid;

final class MarkOrderPaid
{
    private $orderId;

    public function __construct(string $orderId)
    {
        $this->orderId = $orderId;
    }
}
