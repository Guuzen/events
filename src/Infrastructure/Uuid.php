<?php

namespace App\Infrastructure;

/**
 * @template T
 */
abstract class Uuid
{
    /**
     * @var string
     */
    private $id;

    final public function __construct(string $id)
    {
        if (!\Ramsey\Uuid\Uuid::isValid($id)) {
            throw new \RuntimeException(sprintf('invalid uuid: %s', $id));
        }

        $this->id = $id;
    }

    /**
     * @return static
     */
    public static function new()
    {
        return new static(\Ramsey\Uuid\Uuid::uuid4()->toString());
    }

    /**
     * @psalm-param T $uuid
     */
    public function equals($uuid): bool
    {
        return $this->id === $uuid->id;
    }

    final public function __toString(): string
    {
        return $this->id;
    }
}
