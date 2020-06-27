<?php

namespace App\Promocode\Model\AllowedTariffs;

use App\Infrastructure\Persistence\DBALTypes\JsonDocumentType;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomTypeAnnotation as DBALType;
use App\Tariff\Model\TariffId;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

/**
 * @DBALType(typeClass=JsonDocumentType::class)
 *
 * @DiscriminatorMap(
 *     typeProperty="type",
 *     mapping={
 *         "event": "App\Promocode\Model\AllowedTariffs\EventAllowedTariffs",
 *         "specific": "App\Promocode\Model\AllowedTariffs\SpecificAllowedTariffs"
 *     }
 * )
 */
interface AllowedTariffs
{
    public function contains(TariffId $tariffId): bool;
}
