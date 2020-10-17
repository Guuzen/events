<?php

declare(strict_types=1);

namespace Tests\Infrastructure\JoinResponse;

use App\Infrastructure\JoinResponse\Collection;
use App\Infrastructure\JoinResponse\JoinResponse;
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
        $oneCollection = [
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
        $manyCollection = [
            $manyFirstItem,
            $manySecondItem,
        ];

        $result = (new JoinResponse())->oneToMany(
            SingleJoinedOneToManyResponse::class,
            new Collection($oneCollection, fn(array $oneItem) => $oneItem['id']),
            new Collection($manyCollection, fn(array $manyItem) => $manyItem['oneId'])
        );

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
        $oneCollection = [
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
        $firstManyCollection = [
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
        $secondManyCollection = [
            $secondManyFirstItem,
            $secondManySecondItem,
        ];

        $result = (new JoinResponse())->oneToMany(
            DoubleJoinedOneToManyResponse::class,
            new Collection($oneCollection, fn(array $one) => $one['id']),
            new Collection($firstManyCollection, fn(array $manyItem) => $manyItem['oneId']),
            new Collection($secondManyCollection, fn(array $manyItem) => $manyItem['oneId'])
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
        $firstOneCollection = [
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
        $secondOneCollection = [
            $secondOneFirstItem,
            $secondOneSecondItem,
        ];

        $result = (new JoinResponse())->oneToOne(
            SingleJoinedOneToOneResponse::class,
            new Collection($firstOneCollection, fn(array $oneItem) => $oneItem['id']),
            new Collection($secondOneCollection, fn(array $oneItem) => $oneItem['firstOneId'])
        );

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
        $firstOneCollection = [
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
        $secondOneCollection = [
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
        $thirdOneCollection = [
            $thirdOneFirstItem,
            $thirdOneSecondItem,
        ];

        $result = (new JoinResponse())->oneToOne(
            DoubleJoinedOneToOneResponse::class,
            new Collection($firstOneCollection, fn(array $oneItem) => $oneItem['id']),
            new Collection($secondOneCollection, fn(array $oneItem) => $oneItem['firstOneId']),
            new Collection($thirdOneCollection, fn(array $oneItem) => $oneItem['firstOneId'])
        );

        self::assertEquals(
            [
                new DoubleJoinedOneToOneResponse($firstOneFirstItem, $secondOneFirstItem, $thirdOneFirstItem),
                new DoubleJoinedOneToOneResponse($firstOneSecondItem, $secondOneSecondItem, $thirdOneSecondItem),
            ],
            $result
        );
    }
}
