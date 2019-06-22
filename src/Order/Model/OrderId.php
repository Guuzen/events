<?php
declare(strict_types=1);

namespace App\Order\Model;

use Ramsey\Uuid\Uuid;

final class OrderId
{
    private $id;

    // TODO сделать все id создаваемыми из строки
    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function __toString(): string
    {
        return $this->id->toString();
    }
}
