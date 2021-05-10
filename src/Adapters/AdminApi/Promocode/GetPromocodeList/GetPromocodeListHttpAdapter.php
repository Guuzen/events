<?php

declare(strict_types=1);

namespace App\Adapters\AdminApi\Promocode\GetPromocodeList;

use App\Infrastructure\Http\AppController\AppController;
use App\Infrastructure\Persistence\ResultSetMapping;
use Doctrine\DBAL\Connection;
use Guuzen\ResourceComposer\ResourceComposer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetPromocodeListHttpAdapter extends AppController
{
    private $composer;

    private $connection;

    public function __construct(ResourceComposer $composer, Connection $connection)
    {
        $this->composer   = $composer;
        $this->connection = $connection;
    }

    /**
     * @Route("/admin/promocode/list", methods={"GET"})
     */
    public function __invoke(GetPromocodeListRequest $request): Response
    {
        /** @var \Doctrine\DBAL\Driver\PDO\Connection $pdo */
        $pdo = $this->connection->getWrappedConnection();

        $stmt = $pdo->prepare(
            '
            select
                *        
            from
                promocode
            where
                event_id = :event_id
            '
        );
        $stmt->bindValue('event_id', $request->eventId);
        $stmt->execute();

        /** @var array<int, array> $promocodes */
        $promocodes = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $mapping    = ResultSetMapping::forStatement($stmt);
        $promocodes = $mapping->mapKnownColumnsArray($this->connection->getDatabasePlatform(), $promocodes);

        $promocodes = $this->composer->compose($promocodes, 'promocode');

        return $this->response($promocodes);
    }
}
