<?php

declare(strict_types=1);

namespace App\Adapters\AdminApi\Promocode\GetPromocodeList;

use App\Infrastructure\Http\AppController\AppController;
use App\Infrastructure\ResComposer\ResourceComposer;
use App\Adapters\AdminApi\Promocode\Resource\PromocodeResource;
use Doctrine\DBAL\Connection;
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
        $stmt = $this->connection->prepare(
            '
            select
                json_agg(promocodes)
            from (                
                select
                    *        
                from
                    promocode
                where
                    event_id = :event_id
            ) as promocodes
        '
        );
        $stmt->bindValue('event_id', $request->eventId);
        $stmt->execute();

        /** @var string|false $promocodesData */
        $promocodesData = $stmt->fetchOne();
        if ($promocodesData === false) {
            throw new \RuntimeException('promocode list not found');
        }

        /** @var array<int, array> $promocodes */
        $promocodes = $this->deserializeFromDb($promocodesData);

        $resources = $this->composer->compose($promocodes, 'promocode');

        return $this->validateResponse($resources);
    }
}
