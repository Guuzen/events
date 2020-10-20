<?php

declare(strict_types=1);

namespace Tests\Infrastructure\JoinResponse;

use App\Infrastructure\JoinResponse\Collection;
use PHPUnit\Framework\TestCase;

/**
 * TODO rename variables. Try to emulate some domain for better code understanding. For example one person may have many pics etc.
 */
final class JoinResponseTest extends TestCase
{
    public function testSingleJoinOneToMany(): void
    {
        $oneFirstItem  = ['id' => 1];
        $oneSecondItem = ['id' => 2];
        $oneItems      = [
            $oneFirstItem,
            $oneSecondItem,
        ];

        $manyFirstItem  = [
            'id'    => 1,
            'oneId' => 1,
        ];
        $manySecondItem = [
            'id'    => 2,
            'oneId' => 2,
        ];
        $manyItems      = [
            $manyFirstItem,
            $manySecondItem,
        ];

        $oneCollection  = new Collection($oneItems, fn(array $oneItem) => $oneItem['id']);
        $manyCollection = new Collection($manyItems, fn(array $manyItem) => $manyItem['oneId']);
        $result         = $oneCollection->eachToMany(SingleJoinedOneToManyResponse::class, $manyCollection);

        self::assertEquals(
            [
                new SingleJoinedOneToManyResponse($oneFirstItem, [$manyFirstItem]),
                new SingleJoinedOneToManyResponse($oneSecondItem, [$manySecondItem]),
            ],
            $result
        );
    }

    public function testDoubleJoinOneToMany(): void
    {
        $oneFirstItem  = ['id' => 1];
        $oneSecondItem = ['id' => 2];
        $oneItems      = [
            $oneFirstItem,
            $oneSecondItem,
        ];

        $firstManyFirstItem  = [
            'id'    => 10,
            'oneId' => 1,
        ];
        $firstManySecondItem = [
            'id'    => 20,
            'oneId' => 2,
        ];
        $firstManyItems      = [
            $firstManyFirstItem,
            $firstManySecondItem,
        ];

        $secondManyFirstItem  = [
            'id'    => 30,
            'oneId' => 1,
        ];
        $secondManySecondItem = [
            'id'    => 40,
            'oneId' => 2,
        ];
        $secondManyItems      = [
            $secondManyFirstItem,
            $secondManySecondItem,
        ];

        $oneCollection        = new Collection($oneItems, fn(array $one) => $one['id']);
        $firstManyCollection  = new Collection($firstManyItems, fn(array $manyItem) => $manyItem['oneId']);
        $secondManyCollection = new Collection($secondManyItems, fn(array $manyItem) => $manyItem['oneId']);

        $result = $oneCollection->eachToMany(
            DoubleJoinedOneToManyResponse::class,
            $firstManyCollection,
            $secondManyCollection
        );

        self::assertEquals(
            [
                new DoubleJoinedOneToManyResponse($oneFirstItem, [$firstManyFirstItem], [$secondManyFirstItem]),
                new DoubleJoinedOneToManyResponse($oneSecondItem, [$firstManySecondItem], [$secondManySecondItem]),
            ],
            $result
        );
    }

    public function testSingleJoinOneToOne(): void
    {
        $firstOneFirstItem  = ['id' => 1];
        $firstOneSecondItem = ['id' => 2];
        $firstOneItems      = [
            $firstOneFirstItem,
            $firstOneSecondItem,
        ];

        $secondOneFirstItem  = [
            'id'         => 10,
            'firstOneId' => 1,
        ];
        $secondOneSecondItem = [
            'id'         => 20,
            'firstOneId' => 2,
        ];
        $secondOneItems      = [
            $secondOneFirstItem,
            $secondOneSecondItem,
        ];

        $firstOneCollection = new Collection($firstOneItems, fn(array $oneItem) => $oneItem['id']);
        $seoncOneCollection = new Collection($secondOneItems, fn(array $oneItem) => $oneItem['firstOneId']);

        $result = $firstOneCollection->eachToOne(SingleJoinedOneToOneResponse::class, $seoncOneCollection);
        self::assertEquals(
            [
                new SingleJoinedOneToOneResponse($firstOneFirstItem, $secondOneFirstItem),
                new SingleJoinedOneToOneResponse($firstOneSecondItem, $secondOneSecondItem),
            ],
            $result
        );
    }

    public function testDoubleJoinOneToOne(): void
    {
        $firstOneFirstItem  = ['id' => 1];
        $firstOneSecondItem = ['id' => 2];
        $firstOneItems      = [
            $firstOneFirstItem,
            $firstOneSecondItem,
        ];

        $secondOneFirstItem  = [
            'id'         => 10,
            'firstOneId' => 1,
        ];
        $secondOneSecondItem = [
            'id'         => 20,
            'firstOneId' => 2,
        ];
        $secondOneItems      = [
            $secondOneFirstItem,
            $secondOneSecondItem,
        ];

        $thirdOneFirstItem  = [
            'id'         => 100,
            'firstOneId' => 1,
        ];
        $thirdOneSecondItem = [
            'id'         => 200,
            'firstOneId' => 2,
        ];
        $thirdOneItems      = [
            $thirdOneFirstItem,
            $thirdOneSecondItem,
        ];

        $firstOneCollection  = new Collection($firstOneItems, fn(array $oneItem) => $oneItem['id']);
        $secondOneCollection = new Collection($secondOneItems, fn(array $oneItem) => $oneItem['firstOneId']);
        $thirdOneCollection  = new Collection($thirdOneItems, fn(array $oneItem) => $oneItem['firstOneId']);

        $result = $firstOneCollection->eachToOne(
            DoubleJoinedOneToOneResponse::class,
            $secondOneCollection,
            $thirdOneCollection
        );

        self::assertEquals(
            [
                new DoubleJoinedOneToOneResponse($firstOneFirstItem, $secondOneFirstItem, $thirdOneFirstItem),
                new DoubleJoinedOneToOneResponse($firstOneSecondItem, $secondOneSecondItem, $thirdOneSecondItem),
            ],
            $result
        );
    }

    public function testSingleJoinOneToPossiblyNullOne(): void
    {
        $firstOneFirstItem  = ['id' => 1];
        $firstOneSecondItem = ['id' => 2];
        $firstOneItems      = [
            $firstOneFirstItem,
            $firstOneSecondItem,
        ];

        $secondOneFirstItem = [
            'id'         => 10,
            'firstOneId' => 1,
        ];
        $secondOneItems     = [
            $secondOneFirstItem,
        ];

        $firstOneCollection  = new Collection($firstOneItems, fn(array $oneItem) => $oneItem['id']);
        $secondOneCollection = new Collection($secondOneItems, fn(array $oneItem) => $oneItem['firstOneId']);

        $result = $firstOneCollection->eachToOne(SingleJoinedOneToPossiblyNullOneResponse::class, $secondOneCollection);

        self::assertEquals(
            [
                new SingleJoinedOneToPossiblyNullOneResponse($firstOneFirstItem, $secondOneFirstItem),
                new SingleJoinedOneToPossiblyNullOneResponse($firstOneSecondItem, null),
            ],
            $result
        );
    }

    public function testJoinOneWithoutSecondOneKey(): void
    {
        $firstOneFirstItem = [
            'id'          => 1,
            'secondOneId' => null,
        ];
        $firstOneItems     = [
            $firstOneFirstItem,
        ];

        $secondOneFirstItem = [
            'id' => 10,
        ];
        $secondOneItems     = [
            $secondOneFirstItem,
        ];

        $firstOneCollection  = new Collection($firstOneItems, fn(array $oneItem) => $oneItem['secondOneId']);
        $secondOneCollection = new Collection($secondOneItems, fn(array $oneItem) => $oneItem['id']);

        $result = $firstOneCollection->eachToOne(SingleJoinedOneToPossiblyNullOneResponse::class, $secondOneCollection);

        self::assertEquals(
            [
                new SingleJoinedOneToPossiblyNullOneResponse($firstOneFirstItem, null),
            ],
            $result
        );
    }

    public function testCollectionGetKeys(): void
    {
        $collection = new Collection(
            [
                ['key' => 1]
            ],
            fn(array $item) => $item['key']
        );

        self::assertEquals([1], $collection->getKeys());
    }

    public function testCollectionGetOnlyUniqueKeys(): void
    {
        $collection = new Collection(
            [
                ['key' => 1],
                ['key' => 1],
            ],
            fn(array $item) => $item['key']
        );

        self::assertEquals([1], $collection->getKeys());
    }

    public function testCollectionGetKeysWithoutNulls(): void
    {
        $collection = new Collection(
            [
                ['key' => 1],
                ['key' => null],
            ],
            fn(array $item) => $item['key']
        );

        self::assertEquals([1], $collection->getKeys());
    }
}
