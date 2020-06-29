<?php

declare(strict_types=1);

namespace App\EventDomain\Queries\GetEventList;

use App\Infrastructure\Http\AppController;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetEventListHttpAdapter extends AppController
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @Route("/admin/eventDomain/list")
     */
    public function getEventList(): Response
    {
        $stmt = $this->connection->query('
            select
                *
            from
                event_domain
        ');

        return $this->response($stmt->fetchAll());
    }
}
