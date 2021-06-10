<?php

declare(strict_types=1);

namespace App\Adapters\AdminApi\Promocode;

use App\Infrastructure\Persistence\ResourceComposer\PostgresLoader;
use Doctrine\DBAL\Connection;
use Guuzen\ResourceComposer\RelatedResource;
use Guuzen\ResourceComposer\ResourceLoader;

final class PromocodeResource implements RelatedResource
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function loader(): ResourceLoader
    {
        return new PostgresLoader($this->connection, 'promocode', 'id', ['id', 'discount']);
    }

    public function resource(): string
    {
        return self::class;
    }
}
