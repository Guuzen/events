<?php
declare(strict_types=1);

namespace App\Order\Model;

use Ramsey\Uuid\Uuid;

final class ProductId
{
    private $id;

    // TODO сделать все id создаваемыми из строки
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function create(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public function __toString(): string
    {
        return $this->id->toString();
    }
}
