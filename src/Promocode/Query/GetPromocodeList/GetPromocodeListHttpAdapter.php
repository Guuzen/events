<?php

declare(strict_types=1);

namespace App\Promocode\Query\GetPromocodeList;

use App\Infrastructure\Http\AppController;
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
                id,
                event_id as "eventId",
                code,
                json_build_object(
                    \'amount\', discount -> \'amount\' -> \'amount\',
                    \'currency\', discount -> \'amount\' -> \'currency\' -> \'code\',
                    \'type\', discount -> \'type\'
                ) as discount,
                use_limit as "useLimit",
                concat(expire_at, \'Z\') as "expireAt",
                usable,
                used_in_orders as "usedInOrders",
                allowed_tariffs as "allowedTariffs"
            from
                promocode
            where
                event_id = :event_id
        ');
        $stmt->bindValue('event_id', $request->eventId);
        $stmt->execute();

        $promocodes = [];
        /** @psalm-var array{discount: string, usedInOrders: string, allowedTariffs: string} $promocodeData */
        foreach ($stmt->fetchAll() as $promocodeData) {
            $promocode = $promocodeData;

            /** @var array $discount */
            $discount              = \json_decode($promocodeData['discount'], true);
            $promocode['discount'] = $discount;

            /** @var array $usedInOrders */
            $usedInOrders              = \json_decode($promocodeData['usedInOrders'], true);
            $promocode['usedInOrders'] = $usedInOrders;

            /** @var array $allowedTariffs */
            $allowedTariffs              = \json_decode($promocodeData['allowedTariffs'], true);
            $promocode['allowedTariffs'] = $allowedTariffs;
            $promocodes[]                = $promocode;
        }

        return $this->response($promocodes);
    }
}
