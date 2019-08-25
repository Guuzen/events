<?php

namespace App\User\Model;

use Ramsey\Uuid\Uuid;

final class UserId
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
        // TODO validate uuid ?
        return new self(Uuid::fromString($id)->toString());
    }

    public function equals(UserId $userId): bool
    {
        return $this->id === $userId->id;
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
