<?php

declare(strict_types=1);

namespace App\TariffDetails\Query\FindTariffDetailsById;

use App\Infrastructure\Http\AppController;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class FindTariffDetailsHttpAdapter extends AppController
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @Route("/admin/tariffDetails/show", methods={"GET"})
     */
    public function __invoke(FindTariffDetailsByIdRequest $request): Response
    {
        $stmt = $this->connection->prepare('
            select
                id,
                tariff_type as "tariffType"
            from
                tariff_details
            where
                id = :tariff_id
        ');

        $stmt->bindValue('tariff_id', $request->tariffId);
        $stmt->execute();

        /** @var array|false $tariffDetails */
        $tariffDetails = $stmt->fetch();

        return $this->response($tariffDetails);
    }
}
