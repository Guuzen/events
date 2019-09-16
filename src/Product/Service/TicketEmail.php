<?php

namespace App\Product\Service;

/**
 * @psalm-immutable
 */
final class TicketEmail
{
    public $email;

    public $number;

    public function __construct(string $email, string $number)
    {
        $this->email  = $email;
        $this->number = $number;
    }
}