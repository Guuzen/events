<?php

declare(strict_types=1);

namespace App\Model\TariffDescription;

use App\Infrastructure\Http\AppController\AppController;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class TariffDescriptionHttpAdapter extends AppController
{
    private $connection;

    private $tariffDescriptions;

    public function __construct(Connection $connection, TariffDescriptions $tariffDescriptions)
    {
        $this->connection         = $connection;
        $this->tariffDescriptions = $tariffDescriptions;
    }

    /**
     * @Route("/admin/tariffDescription/{tariffId}", methods={"GET"})
     */
    public function show(FindTariffDescriptionByIdRequest $request): Response
    {
        $tariffDescription = $this->connection->fetchAssociative(
            'select * from tariff_description where id = :id',
            ['id' => $request->tariffId]
        );

        /** @psalm-suppress PossiblyFalseArgument */
        return $this->response($tariffDescription);
    }

    /**
     * @Route("/admin/tariffDescription", methods={"POST"})
     */
    public function create(TariffDescription $tariffDescription): Response
    {
        $this->tariffDescriptions->add($tariffDescription);

        $this->flush();

        return $this->response([]);
    }
}
