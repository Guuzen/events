<?php

namespace App\Queries;

use Doctrine\DBAL\Connection;

// TODO awful naming ?
final class FindDataForSendTicketByEmail
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    // TODO delivery address in order ?

    /**
     * @psalm-return array{email: string, number: string}|DataForSendTicketByEmailNotFound
     */
    public function __invoke(string $orderId)
    {
        $stmt = $this->connection->prepare('
            select
                "user".contacts ->> \'email\' as email,
                ticket.number
            from
                "order"
            left join
                "user" on "user".id = "order".user_id
            left join
                ticket on ticket.id = "order".product_id
            where "order".id = :order_id
        ');
        $stmt->bindValue('order_id', $orderId);
        $stmt->execute();

        /** @var array{email: string, number: string}|false */
        $result = $stmt->fetch();

        if (false === $result) {
            return new DataForSendTicketByEmailNotFound();
        }

        return $result;
    }
}
