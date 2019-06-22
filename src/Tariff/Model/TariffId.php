<?php
declare(strict_types=1);

namespace App\Tariff\Model;

use Ramsey\Uuid\Uuid;

final class TariffId
{
    private $id;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function equals(TariffId $tariffId): bool
    {
        return $this->id->equals($tariffId->id);
    }

    public function asString(): string
    {
        return $this->id->toString();
    }

    // TODO remove all __toString?
    public function __toString(): string
    {
        return $this->asString();
    }
}
