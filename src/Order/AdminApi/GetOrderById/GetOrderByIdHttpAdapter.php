<?php

declare(strict_types=1);

namespace App\Order\AdminApi\GetOrderById;

use App\Infrastructure\Http\AppController\AppController;
use App\Infrastructure\ResComposer\ResourceComposer;
use App\Order\AdminApi\OrderResource;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetOrderByIdHttpAdapter extends AppController
{
    private $connection;

    private $composer;

    public function __construct(Connection $connection, ResourceComposer $composer)
    {
        $this->connection = $connection;
        $this->composer   = $composer;
    }

    /**
     * @Route("/admin/order/{orderId}", methods={"GET"})
     */
    public function __invoke(GetOrderByIdRequest $request): Response
    {
        $stmt = $this->connection->prepare(
            '
            select
                row_to_json("order")
            from (
                select
                    *
                from
                    "order"
                where
                    "order".id = :order_id                 
            ) as "order"
        '
        );
        $stmt->bindValue('order_id', $request->orderId);
        $stmt->execute();

        /** @var string $orderData */
        $orderData = $stmt->fetchOne();

        $order = $this->deserializeFromDb($orderData);

        $resource = $this->composer->composeOne($order, 'order');

        return $this->response($resource);
    }
}
