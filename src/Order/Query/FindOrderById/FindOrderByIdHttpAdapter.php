<?php

declare(strict_types=1);

namespace App\Order\Query\FindOrderById;

use App\Infrastructure\Http\AppController\AppController;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class FindOrderByIdHttpAdapter extends AppController
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @Route("/admin/order/{order_id}", methods={"GET"})
     */
    public function __invoke(FindOrderByIdRequest $request): Response
    {
        $stmt = $this->connection->prepare('
            select
                json_build_object(
                    \'data\',
                    row_to_json("order")
                ) as json
            from (
                select
                    *
                from
                    "order"
                where
                    "order".id = :order_id                 
            ) as "order"
        ');
        $stmt->bindValue('order_id', $request->orderId);
        $stmt->execute();

        /** @psalm-var array{json: string} $orderData */
        $orderData = $stmt->fetch();

        return $this->toJsonResponse($orderData['json']);
    }
}
