<?php

namespace App\Queries\Event;

use Doctrine\DBAL\Connection;

final class EventQueries
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findAll(): array
    {
        $stmt = $this->connection->query('
            select
                *
            from
                event_config
        ');

        return $stmt->fetchAll();
    }

    public function findById(string $eventId): array
    {
        $stmt = $this->connection->prepare('
            select
                *
            from
                event_config
            where
                event_config.id = :event_id
        ');
        $stmt->bindValue('event_id', $eventId);
        $stmt->execute();

        /** @var array */
        return $stmt->fetch();
    }
}
