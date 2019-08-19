<?php

namespace App\Tests\AppResponse\TariffById;

final class TariffByIdBuilder
{
    private $id;

    private $productType;

    private $price;

    private $termStart;

    private $termEnd;

    private function __construct(?string $id, string $productType, string $price, string $termStart, string $termEnd)
    {
        $this->id          = $id;
        $this->productType = $productType;
        $this->price       = $price;
        $this->termStart   = $termStart;
        $this->termEnd     = $termEnd;
    }

    public static function any(): self
    {
        return new self(null, 'silver_pass', '200 RUB', '2000-01-01 12:00:00', '3000-01-01 12:00:00');
    }

    public function build(): TariffById
    {
        return new TariffById($this->id, $this->productType, $this->price, $this->termStart, $this->termEnd);
    }

    public function withId(string $id): self
    {
        return new self($id, $this->productType, $this->price, $this->termStart, $this->termEnd);
    }

    public function withProductType(string $productType): self
    {
        return new self($this->id, $productType, $this->price, $this->termStart, $this->termEnd);
    }

    public function withPrice(string $price): self
    {
        return new self($this->id, $this->productType, $price, $this->termStart, $this->termEnd);
    }

    public function withTermStart(string $termStart): self
    {
        return new self($this->id, $this->productType, $this->price, $termStart, $this->termEnd);
    }

    public function withTermEnd(?string $termEnd): self
    {
        return new self($this->id, $this->productType, $this->price, $this->termStart, $termEnd);
    }
}
