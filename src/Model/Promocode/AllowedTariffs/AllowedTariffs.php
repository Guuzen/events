<?php

namespace App\Model\Promocode\AllowedTariffs;

use App\Infrastructure\Persistence\DBALTypes\JsonDocumentType;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomTypeAnnotation as DBALType;
use App\Model\Tariff\TariffId;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

/**
 * @DBALType(typeClass=JsonDocumentType::class)
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
