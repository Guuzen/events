<?php

declare(strict_types=1);

namespace Tests\Infrastructure\JoinResponse\Isolated;

use App\Infrastructure\ResponseComposer\ResourceLink\OneToMany;
use App\Infrastructure\ResponseComposer\ResourceLink\OneToOne;
use App\Infrastructure\ResponseComposer\ResponseBuilder\GroupBuilder;
use App\Infrastructure\ResponseComposer\ResponseBuilder\SingleBuilder;
use PHPUnit\Framework\TestCase;

final class GroupBuilderTest extends TestCase
{
    public function testOneToOneGroupZeroBuilders(): void
    {
        $link = new OneToOne(
            fn(array $foo) => (string)$foo['id']
        );

        $grouped = $link->group([]);

        self::assertEquals([], $grouped);
    }

    public function testOneToOneGroupOneBuilder(): void
    {
        $link       = new OneToOne(
            fn(array $resource) => (string)$resource['id']
        );
        $resourceId = '1';
        $builder    = new SingleBuilder(\stdClass::class, ['id' => $resourceId], []);

        $grouped = $link->group([$builder]);

        self::assertEquals([$resourceId => $builder], $grouped);
    }

    public function testOneToManyGroupZeroBuilders(): void
    {
        $link = new OneToMany(
            fn(array $resource) => (string)$resource['id']
        );

        $grouped = $link->group([]);

        self::assertEquals([], $grouped);
    }

    public function testOnetoManyGroupOneBuilder(): void
    {
        $link       = new OneToMany(
            fn(array $resource) => (string)$resource['id']
        );
        $resourceId = '1';
        $builder    = new SingleBuilder(\stdClass::class, ['id' => $resourceId], []);

        $grouped = $link->group([$builder]);

        self::assertEquals(
            [
                $resourceId => new GroupBuilder([$builder])
            ],
            $grouped
        );
    }
}
