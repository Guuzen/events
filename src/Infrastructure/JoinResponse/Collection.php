<?php

declare(strict_types=1);

namespace App\Infrastructure\JoinResponse;

/**
 * @template TKey of string|null
 * @template TValue
 */
final class Collection implements \Iterator
{
    /**
     * @var array<array-key, TValue>
     */
    private $items;

    /**
     * @var callable(TValue): TKey $keyExtractor
     */
    private $keyExtractor;

    /**
     * @var int
     */
    private $position;

    /**
     * @psalm-param array<array-key, TValue> $items
     * @psalm-param callable(TValue): TKey $keyExtractor
     */
    public function __construct(array $items, callable $keyExtractor)
    {
        $this->items        = $items;
        $this->keyExtractor = $keyExtractor;
        $this->position     = 0;
    }

    /**
     * @psalm-return TValue
     */
    public function current()
    {
        return $this->items[$this->position];
    }

    public function next(): void
    {
        $this->position++;
    }

    /**
     * @return array<array-key, TKey>
     */
    public function getKeys(): array
    {
        $keys = [];
        foreach ($this->items as $item) {
            $keys[] = ($this->keyExtractor)($item);
        }

        $uniqueKeys = \array_unique($keys);

        $filteredKeys = \array_filter($uniqueKeys);

        return $filteredKeys;
    }

    /**
     * @template T1
     * @template T2
     *
     * @param class-string<T1> $into
     * @psalm-param self<TKey, T2> $one
     *
     * @psalm-return iterable<T1>
     */
    public function eachToOne(string $into, self ...$one): iterable
    {
        $result = [];
        foreach ($this as $key => $value) {
            if ($key === null) {
                /** @psalm-suppress MixedMethodCall */
                $result[] = new $into($value, null);
            } else {
                $foundOnes = [];
                foreach ($one as $oneItem) {
                    $foundOnes[] = $this->findOne($oneItem, $key);
                }

                /** @psalm-suppress MixedMethodCall */
                $result[] = new $into($value, ...$foundOnes);
            }
        }

        return $result;
    }

    /**
     * @template T1
     * @template T2
     *
     * @param class-string<T1> $into
     * @psalm-param self<TKey, T2> $many
     *
     * @psalm-return iterable<T1>
     */
    public function eachToMany(string $into, self ...$many): iterable
    {
        $result = [];
        foreach ($this as $oneKey => $oneItem) {
            $manyResult = [];
            foreach ($many as $manyItem) {
                $manyResult[] = $this->filterCollection($manyItem, $oneKey);
            }

            /** @psalm-suppress MixedMethodCall */
            $result[] = new $into($oneItem, ...$manyResult);
        }

        return $result;
    }

    /**
     * @psalm-return TKey
     */
    public function key()
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

    /**
     * @template T
     *
     * @psalm-param self<TKey, T> $collection
     * @psalm-param TKey $findBy
     *
     * @psalm-return T|null
     */
    private function findOne(self $collection, $findBy)
    {
        foreach ($collection as $key => $value) {
            if ($key === $findBy) {
                return $value;
            }
        }

        return null;
    }

    /**
     * @template T
     *
     * @psalm-param self<TKey, T> $collection
     * @psalm-param TKey $filterBy
     *
     * @psalm-return T[]
     */
    private function filterCollection(self $collection, $filterBy)
    {
        $result = [];
        foreach ($collection as $key => $item) {
            if ($key === $filterBy) {
                $result[] = $item;
            }
        }

        return $result;
    }
}
