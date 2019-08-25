<?php

namespace App\Product\Model;

use Ramsey\Uuid\Uuid;

final class BroadcastLinkId
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

    public function equals(BroadcastLinkId $broadcastLinkId): bool
    {
        return $this->id === $broadcastLinkId->id;
    }

    public function asString(): string
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->asString();
    }
}
