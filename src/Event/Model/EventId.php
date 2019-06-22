<?php
declare(strict_types=1);

namespace App\Event\Model;

use Ramsey\Uuid\Uuid;

final class EventId
{
    private $id;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

//    private function __construct(Uuid $id)
//    {
//        $this->id = $id;
//    }
//
//    public function new(): self
//    {
//        return new self(Uuid::uuid4());
//    }
//
//    public function fromString(string $id): self
//    {
//        if (!Uuid::isValid($id)) {
//            throw new EventIdIsNotValid();
//        }
//
//        return new self(Uuid::fromString($id));
//    }

    public function equals(EventId $eventId): bool
    {
        return $this->id->equals($eventId->id);
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
