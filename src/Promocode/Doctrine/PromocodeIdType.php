<?php

namespace App\Promocode\Doctrine;

use App\Infrastructure\Persistence\UuidType;
use App\Promocode\Model\PromocodeId;

/**
 * @template-extends UuidType<PromocodeId>
 */
final class PromocodeIdType extends UuidType
{
    public function getName(): string
    {
        return 'app_promocode_id';
    }

    protected function className(): string
    {
        return PromocodeId::class;
    }
}
