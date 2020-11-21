<?php

declare(strict_types=1);

namespace Tests\Infrastructure\JoinResponse\Isolated\BuildResponses;

use PHPUnit\Framework\TestCase;
use App\Infrastructure\ResponseComposer\ResponseBuilder\GroupBuilder;
use App\Infrastructure\ResponseComposer\ResponseBuilder\SingleBuilder;

final class BuildResponsesTest extends TestCase
{
    public function testBuildResponseWithoutLinks(): void
    {
        $resource = ['a' => 'b'];
        $builder  = new SingleBuilder(ResponseWithoutLinks::class, $resource, []);

        $response = $builder->build();

        self::assertEquals(new ResponseWithoutLinks($resource), $response);
    }

    public function testBuildResponseWithOneLink(): void
    {
        $resource       = ['a' => 'b'];
        $linkedResource = ['c' => 'd'];
        $builder        = new SingleBuilder(
            ResponseWithOneLink::class,
            $resource,
            [
                new SingleBuilder(ResponseWithoutLinks::class, $linkedResource, [])
            ]
        );

        $response = $builder->build();

        self::assertEquals(new ResponseWithOneLink($resource, new ResponseWithoutLinks($linkedResource)), $response);
    }

    public function testBuildResponseWithTwoLinks(): void
    {
        $resource        = ['a' => 'b'];
        $linkedResource1 = ['c' => 'd'];
        $linkedResource2 = ['e' => 'f'];
        $builder         = new SingleBuilder(
            ResponseWithTwoLinks::class,
            $resource,
            [
                new SingleBuilder(ResponseWithoutLinks::class, $linkedResource1, []),
                new SingleBuilder(ResponseWithoutLinks::class, $linkedResource2, []),
            ]
        );

        $response = $builder->build();

        self::assertEquals(
            new ResponseWithTwoLinks(
                $resource,
                new ResponseWithoutLinks($linkedResource1),
                new ResponseWithoutLinks($linkedResource2)
            ),
            $response
        );
    }

    public function testBuildGroupOfResponses(): void
    {
        $resource1 = ['a' => 'b'];
        $resource2 = ['b' => 'c'];
        $builder1  = new SingleBuilder(ResponseWithoutLinks::class, $resource1, []);
        $builder2  = new SingleBuilder(ResponseWithoutLinks::class, $resource2, []);
        $builder   = new GroupBuilder([$builder1, $builder2]);

        $responses = $builder->build();

        self::assertEquals(
            [
                new ResponseWithoutLinks($resource1),
                new ResponseWithoutLinks($resource2),
            ],
            $responses
        );
    }

    public function testBuildEmptyGroupOfResponses(): void
    {
        $builder = new GroupBuilder([]);

        $responses = $builder->build();

        self::assertEquals([], $responses);
    }
}
