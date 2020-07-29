<?php

namespace App\Promocode\ViewModel\AllowedTariffs;

use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

/**
 * @DiscriminatorMap(
 *     typeProperty="type",
 *     mapping={
 *         "event": "App\Promocode\ViewModel\AllowedTariffs\EventAllowedTariffs",
 *         "specific": "App\Promocode\ViewModel\AllowedTariffs\SpecificAllowedTariffs"
 *     }
 * )
 */
interface AllowedTariffs
{
}
