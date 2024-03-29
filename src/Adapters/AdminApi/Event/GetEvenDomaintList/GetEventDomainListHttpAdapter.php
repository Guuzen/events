<?php

declare(strict_types=1);

namespace App\Adapters\AdminApi\Event\GetEvenDomaintList;

use App\Infrastructure\Http\AppController\AppController;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetEventDomainListHttpAdapter extends AppController
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @Route("/admin/eventDomain", methods={"GET"})
     */
    public function getEventList(): Response
    {
        $eventList = $this->connection->fetchAllAssociative(
            '
            select
                *
            from
                event_domain
            '
        );

        return $this->response($eventList);
    }
}
