<?php


namespace App\Order\Doctrine;

use App\Common\JsonDocumentType;
use App\Order\Model\OrderIds;

final class OrderIdsType extends JsonDocumentType
{
    private const TYPE = 'app_order_ids';

    protected function className(): string
    {
        return OrderIds::class;
    }

    public function getName(): string
    {
        return self::TYPE;
    }
}
