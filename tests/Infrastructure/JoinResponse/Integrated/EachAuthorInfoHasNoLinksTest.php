<?php

declare(strict_types=1);

namespace Tests\Infrastructure\JoinResponse\Integrated;

use App\Infrastructure\ResponseComposer\ResourceProviders;
use App\Infrastructure\ResponseComposer\Schema;
use App\Infrastructure\ResponseComposer\SchemaProvider;
use PHPUnit\Framework\TestCase;

final class EachAuthorInfoHasNoLinksTest extends TestCase
{
    public function test(): void
    {
        $authorInfo1 = ['id' => '1'];
        $authorInfo2 = ['id' => '2'];
        $authorsInfo = [
            $authorInfo1,
            $authorInfo2,
        ];
        $builder     = AuthorInfo::schema();

        $groupBuilder = $builder->collect($authorsInfo, new ResourceProviders([]));
        $result       = $groupBuilder->build();

        self::assertEquals([new AuthorInfo($authorInfo1), new AuthorInfo($authorInfo2)], $result);
    }
}

final class AuthorInfo implements SchemaProvider
{
    private array $authorInfo;

    public function __construct(array $authorInfo)
    {
        $this->authorInfo = $authorInfo;
    }

    public static function schema(): Schema
    {
        return new Schema(self::class);
    }
}
