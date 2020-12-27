<?php

declare(strict_types=1);

namespace App\Infrastructure\ArrayComposer\Tests;

use App\Infrastructure\ArrayComposer\Path;
use PHPUnit\Framework\TestCase;

final class PathTest extends TestCase
{
    public function testThereisNothingToExpose(): void
    {
        $array = [];

        /** @psalm-suppress MixedAssignment */
        $result = &(new Path([]))->expose($array);

        self::assertEquals($array, $result);

        $this->assertSameReference($array, $result);
    }

    public function testThereisNoNestedPathToExpose(): void
    {
        $array = ['a' => '1'];

        self::assertEquals(null, (new Path(['a', 'not-exists-path']))->expose($array));
    }

    public function testExposePath(): void
    {
        $array = ['a' => '1'];

        /** @var mixed|null $result */
        $result = &(new Path(['a']))->expose($array);

        self::assertEquals('1', $result);

        $this->assertSameReference($array['a'], $result);
    }

    public function testExpandFailsOnWrongPath(): void
    {
        $this->expectException(\LogicException::class);

        (new Path(['a', 'b', '[]']))->expand(['a' => 'b']);
    }

    public function testExpandRootArray(): void
    {
        $path = new Path(['[]']);

        self::assertEquals([new Path(['0'])], $path->expand(['a']));
    }

    public function testExpandNestedArray(): void
    {
        $path = new Path(['a', '[]']);

        self::assertEquals([new Path(['a', '0'])], $path->expand(['a' => ['b']]));
    }

    public function testExpandRootArrayWithLeaf(): void
    {
        $path = new Path(['[]', 'a']);

        self::assertEquals(
            [
                new Path(['0', 'a']),
                new Path(['1', 'a'])
            ],
            $path->expand(
                [
                    ['a' => 1],
                    ['a' => 1],
                ]
            )
        );
    }

    public function testExpandArrayInMiddle(): void
    {
        $path = new Path(['a', '[]', 'b']);

        self::assertEquals(
            [new Path(['a', '0', 'b'])],
            $path->expand(
                [
                    'a' => [
                        ['b' => '2']
                    ],
                ]
            )
        );
    }

    public function testExpandArrayInArray(): void
    {
        $path = new Path(['a', '[]', '[]']);

        self::assertEquals(
            [
                new Path(['a', '0', '0']),
                new Path(['a', '1', '0']),
            ],
            $path->expand(
                [
                    'a' => [
                        [
                            ['b'],
                        ],
                        [
                            ['c'],
                        ],
                    ],
                ]
            )
        );
    }

    /**
     * @param mixed $val1
     * @param mixed $val2
     *
     * @noinspection PhpParameterByRefIsNotUsedAsReferenceInspection
     */
    private function assertSameReference(&$val1, &$val2, string $with = 'zzz'): void
    {
        $val1 = $with;

        self::assertSame($val1, $val2);
        self::assertSame($val1, $with);
    }
}