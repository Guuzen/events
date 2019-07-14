<?php

namespace App\Order\Model;

use Ramsey\Uuid\Uuid;

final class TariffId
{
    private $id;

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function new(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public static function fromString(string $id): self
    {
        return new self(Uuid::fromString($id)->toString());
    }

    public function equals(TariffId $tariffId): bool
    {
        return $this->id === $tariffId->id;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}
