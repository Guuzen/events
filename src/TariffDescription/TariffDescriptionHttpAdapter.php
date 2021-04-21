<?php

declare(strict_types=1);

namespace App\TariffDescription;

use App\Infrastructure\ArrayKeysNameConverter\ArrayKeysNameConverter;
use App\Infrastructure\Http\AppController\AppController;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class TariffDescriptionHttpAdapter extends AppController
{
    private $connection;
    private $tariffDescriptions;
    private $arrayKeysNameConverter;

    public function __construct(
        Connection $connection,
        ArrayKeysNameConverter $arrayKeysNameConverter,
        TariffDescriptions $tariffDescriptions
    )
    {
        $this->connection             = $connection;
        $this->arrayKeysNameConverter = $arrayKeysNameConverter;
        $this->tariffDescriptions     = $tariffDescriptions;
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
        $tariffDescription = $this->arrayKeysNameConverter->convert($tariffDescription);

        return $this->validateResponse($tariffDescription);
    }

    /**
     * @Route("/admin/tariffDescription", methods={"POST"})
     */
    public function create(TariffDescription $tariffDescription): Response
    {
        $this->tariffDescriptions->add($tariffDescription);

        $this->flush();

        return $this->validateResponse([]);
    }
}
