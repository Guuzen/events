<?php
declare(strict_types=1);

namespace App\Promocode\Model;

use Ramsey\Uuid\Uuid;

final class PromocodeId
{
    private $id;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function asString(): string
    {
        return $this->id->toString();
    }

    public function __toString(): string
    {
        return $this->asString();
    }
}
