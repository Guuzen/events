<?php

namespace App\Tariff\Model;

use Ramsey\Uuid\Uuid;

final class TicketTariffId
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

    public function equals(TicketTariffId $ticketTariffId): bool
    {
        return $this->id === $ticketTariffId->id;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}
