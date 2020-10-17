<?php

declare(strict_types=1);

namespace App\Infrastructure\JoinResponse;

final class Collection implements \Iterator
{
    private $items;

    private $keyExtractor;

    private $position;

    public function __construct(array $items, callable $keyExtractor)
    {
        $this->items        = $items;
        $this->keyExtractor = $keyExtractor;
        $this->position     = 0;
    }

    public function current(): array
    {
        return $this->items[$this->position];
    }

    public function next(): void
    {
        $this->position++;
    }

    public function key(): int
    {
        return ($this->keyExtractor)($this->items[$this->position]);
    }

    public function valid(): bool
    {
        return isset($this->items[$this->position]) === true;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }
}
