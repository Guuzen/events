<?php

declare(strict_types=1);

namespace App\Promocode\Query\GetPromocodeList;

use App\Infrastructure\Http\AppController\AppController;
use App\Promocode\ViewModel\PromocodeList;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetPromocodeListHttpAdapter extends AppController
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }


    /**
     * @Route("/admin/promocode/list", methods={"GET"})
     */
    public function __invoke(GetPromocodeListRequest $request): Response
    {
        $stmt = $this->connection->prepare('
            select
                json_build_object(
                    \'promocodes\', json_agg(promocodes)
                ) as json
            from (                
                select
                    *        
                from
                    promocode
                where
                    event_id = :event_id
            ) as promocodes
        ');
        $stmt->bindValue('event_id', $request->eventId);
        $stmt->execute();

        /** @psalm-var array{json: string} $promocodesData */
        $promocodesData = $stmt->fetchAll()[0];

        $promocodeList = $this->deserializeToViewModel($promocodesData['json'], PromocodeList::class);

        return $this->response($promocodeList);
    }
}
