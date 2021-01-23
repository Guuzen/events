<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests\EachAuthorInfoHasNoLinks;

use App\Infrastructure\ResComposer\Tests\TestCase;

final class EachAuthorInfoHasNoLinksTest extends TestCase
{
    public function test(): void
    {
        $authorInfoId1 = '1';
        $authorInfo1   = ['id' => $authorInfoId1];

        $authorInfoId2 = '2';
        $authorInfo2   = ['id' => $authorInfoId2];

        $authorsInfo = [
            $authorInfo1,
            $authorInfo2,
        ];

        $resources = $this->composer->compose($authorsInfo, AuthorInfo::class);

        self::assertEquals([new AuthorInfo($authorInfoId1), new AuthorInfo($authorInfoId2)], $resources);
    }
}
