<?php

declare(strict_types=1);

namespace App\Infrastructure\JoinResponse;

final class JoinResponse
{
    public function oneToOne(string $into, iterable $firstOne, iterable ...$otherOnes): iterable
    {
        $result = [];
        foreach ($firstOne as $firstOneKey => $firstOneValue) {
            $foundOnes = [];
            foreach ($otherOnes as $otherOne) {
                $foundOne = $this->findOne($otherOne, $firstOneKey);
                if ($foundOne === null) {
                    continue;
                }
                $foundOnes[] = $foundOne;
            }

            $result[] = new $into($firstOneValue, ...$foundOnes);
        }

        return $result;
    }

    public function oneToMany(string $into, iterable $one, iterable ...$many): iterable
    {
        $result = [];
        foreach ($one as $oneKey => $oneItem) {
            $manyResult = [];
            foreach ($many as $manyItem) {
                $manyResult[] = $this->filterCollection($manyItem, $oneKey);
            }
            $result[] = new $into($oneItem, ...$manyResult);
        }

        return $result;
    }

    private function findOne(iterable $collection, int $findBy): ?iterable
    {
        foreach ($collection as $key => $value) {
            if ($key === $findBy) {
                return $value;
            }
        }

        return null;
    }

    private function filterCollection(iterable $collection, int $filterBy): iterable
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
