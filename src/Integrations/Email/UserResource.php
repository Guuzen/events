<?php

declare(strict_types=1);

namespace App\Integrations\Email;

use App\Infrastructure\Persistence\ResourceComposer\PostgresLoader;
use Doctrine\DBAL\Connection;
use Guuzen\ResourceComposer\RelatedResource;
use Guuzen\ResourceComposer\ResourceLoader;

final class UserResource implements RelatedResource
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function loader(): ResourceLoader
    {
        return new PostgresLoader($this->connection, '"user"', 'id');
    }

    public function resource(): string
    {
        return self::class;
    }
}
