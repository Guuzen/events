<?php

declare(strict_types=1);

namespace Tests\Infrastructure\JoinResponse\Integrated;

use App\Infrastructure\ArrayComposer\Path\Path;
use App\Infrastructure\ArrayComposer\ResourceProviders;
use App\Infrastructure\ArrayComposer\Schema;
use PHPUnit\Framework\TestCase;

final class EachAuthorHasManyPostsTest extends TestCase
{
    public function test(): void
    {
        $authorId1 = '1';
        $authorId2 = '2';
        $author1   = ['id' => $authorId1];
        $author2   = ['id' => $authorId2];
        $authors   = [
            $author1,
            $author2,
        ];
        $post1     = ['id' => 'nonsense', 'authorId' => $authorId1];
        $post2     = ['id' => 'nonsense', 'authorId' => $authorId2];
        $posts     = [
            $post1,
            $post2,
        ];
        $schema    = new Schema('authorProvider');
        $schema->oneToMany(
            new Schema('postProvider'),
            new Path(['id']),
            new Path(['authorId']),
            'posts'
        );
        $providers = new ResourceProviders(
            [
                'postProvider' => new StubResourceProvider($posts),
            ]
        );

        $result = $schema->collect($authors, $providers);

        self::assertEquals(
            [
                [
                    'id'    => $authorId1,
                    'posts' => [
                        ['id' => 'nonsense', 'authorId' => $authorId1]
                    ],
                ],
                [
                    'id'    => $authorId2,
                    'posts' => [
                        ['id' => 'nonsense', 'authorId' => $authorId2]
                    ],
                ],
            ],
            $result
        );
    }
}
