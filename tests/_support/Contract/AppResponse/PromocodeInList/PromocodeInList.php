<?php

namespace App\Tests\Contract\PromocodeInList\AppResponse;

final class PromocodeInList
{
    private $id;

    private $code;

    private $discount;

    private $useLimit;

    private $expireAt;

    private $usable;

    private function __construct(
        string $id,
        string $code,
        Discount $discount,
        int $useLimit,
        string $expireAt,
        bool $usable
    ) {
        $this->id         = $id;
        $this->code       = $code;
        $this->discount   = $discount;
        $this->useLimit   = $useLimit;
        $this->expireAt   = $expireAt;
        $this->usable     = $usable;
    }

    public static function anyFixed10RubWith(string $id, string $code): self
    {
        return new self($id, $code, Discount::isFixed10Rub(), 1, '3000-12-12 00:00:00', true);
    }
}
