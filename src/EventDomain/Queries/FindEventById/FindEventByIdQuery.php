<?php

declare(strict_types=1);

namespace App\EventDomain\Queries\FindEventById;

use App\Common\Error;
use Doctrine\DBAL\Connection;

final class FindEventByIdQuery
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return array|Error
     */
    public function __invoke(string $eventId)
    {
        $stmt = $this->connection->prepare('
            select
                *
            from
                event_domain
            where
                event_domain.id = :event_id
        ');
        $stmt->bindValue('event_id', $eventId);
        $stmt->execute();

        /** @var array|false */
        $result = $stmt->fetch();
        if (false === $result) {
            return new EventNotFound();
        }

        return $result;
    }
}
