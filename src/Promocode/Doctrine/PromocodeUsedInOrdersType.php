<?php

namespace App\Promocode\Doctrine;

use App\Common\JsonDocumentType;
use App\Promocode\Model\UsedInOrders;

final class PromocodeUsedInOrdersType extends JsonDocumentType
{
    protected function className(): string
    {
        return UsedInOrders::class;
    }

    public function getName(): string
    {
        return 'app_promocode_used_in_orders';
    }
}
