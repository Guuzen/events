<?php

namespace App\Model\Promocode\AllowedTariffs;

use App\Infrastructure\Persistence\DBALTypes\JsonDocumentType;
use App\Infrastructure\Persistence\DBALTypesInitializer\DbalType;
use App\Model\Tariff\TariffId;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

/**
 * @DbalType(typeClass=JsonDocumentType::class)
 *
 * @DiscriminatorMap(
 *     typeProperty="type",
 *     mapping={
 *         "event": EventAllowedTariffs::class,
 *         "specific": SpecificAllowedTariffs::class,
 *     }
 * )
 */
interface AllowedTariffs
{
    public function contains(TariffId $tariffId): bool;
}
