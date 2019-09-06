<?php

namespace App\Order\Doctrine;

use App\Order\Model\OrderId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class OrderIdType extends StringType
{
    public function convertToPHPValue($value, AbstractPlatform $platform): OrderId
    {
        // TODO create via reflection ?
        return new OrderId((string) $value);
    }

    public function getName(): string
    {
        return 'app_order_id';
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
