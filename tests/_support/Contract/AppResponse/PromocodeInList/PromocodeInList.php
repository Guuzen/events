<?php

namespace App\Tests\Contract\AppResponse\PromocodeInList;

use DateTimeImmutable;

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
        DateTimeImmutable $expireAt,
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
        return new self($id, $code, Discount::isFixed10Rub(), 1, new DateTimeImmutable('3000-12-12 00:00:00'), true);
    }
}
