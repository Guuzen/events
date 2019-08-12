<?php

namespace App\Order\Doctrine;

use App\Common\JsonDocumentType;
use App\Order\Model\OrderIds;

final class OrderIdsType extends JsonDocumentType
{
    protected function className(): string
    {
        return OrderIds::class;
    }

    public function getName(): string
    {
        return 'app_order_ids';
    }
}
