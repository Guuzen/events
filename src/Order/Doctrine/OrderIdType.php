<?php

namespace App\Order\Doctrine;

use App\Order\Model\OrderId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class OrderIdType extends StringType
{
    private const TYPE = 'app_order_id';

    public function convertToPHPValue($value, AbstractPlatform $platform): OrderId
    {
        // TODO create via reflection ?
        return OrderId::fromString($value);
    }

    public function getName(): string
    {
        return self::TYPE;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
