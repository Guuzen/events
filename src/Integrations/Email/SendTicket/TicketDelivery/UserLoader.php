<?php

declare(strict_types=1);

namespace App\Integrations\Email\SendTicket\TicketDelivery;

use App\Infrastructure\Persistence\DatabaseSerializer\DatabaseSerializer;
use Doctrine\DBAL\Connection;
use Guuzen\ResourceComposer\ResourceDataLoader;

final class UserLoader implements ResourceDataLoader
{
    private $connection;

    private $serializer;

    public function __construct(Connection $connection, DatabaseSerializer $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * @psalm-suppress MixedReturnTypeCoercion
     */
    public function load(array $userIds): array
    {
        /** @var string|null $users */
        $users = $this->connection->fetchOne(
            '
            select json_agg(users) from (
                select * from "user" where "user".id in (:user_ids)
            ) as users
        ',
            ['user_ids' => $userIds],
            ['user_ids' => Connection::PARAM_STR_ARRAY],
        );

        if ($users === null) {
            return [];
        }

        return $this->serializer->decode($users);
    }
}
