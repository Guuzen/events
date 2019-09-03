<?php

namespace App\Queries\Event;

use App\Queries\Event\FindEventById\EventById;
use App\Queries\Event\FindEventById\EventByIdNotFound;
use App\Queries\Event\FindEventsInList\EventInList;
use Doctrine\DBAL\Connection;

final class EventQueries
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return EventInList[]
     */
    public function findInList(): array
    {
        $stmt = $this->connection->query('
            select row_to_json(event_config) as json
            from (
                select
                    *
                from
                    event_config
            ) as event_config
        ');

        $events = [];
        /** @psalm-var array{json: string} $eventData */
        foreach ($stmt->fetchAll() as $eventData) {
            /** @var EventInList */
            $event    = $this->connection->convertToPHPValue($eventData['json'], EventInList::class);
            $events[] = $event;
        }

        return $events;
    }

    /**
     * @return EventById|EventByIdNotFound
     */
    public function findById(string $eventId)
    {
        $stmt = $this->connection->prepare('
            select row_to_json(event_config) as json
            from (
                select
                    *
                from
                    event_config
                where
                    event_config.id = :event_id
            ) as event_config
        ');
        $stmt->bindValue('event_id', $eventId);
        $stmt->execute();

        /** @psalm-var array{json: string}|false */
        $result = $stmt->fetch();
        if (false === $result) {
            return new EventByIdNotFound();
        }

        /** @var EventById */
        $event = $this->connection->convertToPHPValue($result['json'], EventById::class);

        return $event;
    }
}
