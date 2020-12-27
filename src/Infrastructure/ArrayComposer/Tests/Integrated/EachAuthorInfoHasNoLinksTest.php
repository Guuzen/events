<?php

declare(strict_types=1);

namespace App\Infrastructure\ArrayComposer\Tests\Integrated;

use App\Infrastructure\ArrayComposer\ResourceProviders;
use App\Infrastructure\ArrayComposer\Schema;
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
        $builder = new Schema();

        $builder->collect($authorsInfo, 'authorInfoProvider', new ResourceProviders([]));

        self::assertEquals([$authorInfo1, $authorInfo2], $authorsInfo);
    }
}
