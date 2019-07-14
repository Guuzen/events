<?php

namespace App\Product\Doctrine;

use App\Order\Model\OrderId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class TicketIdType extends StringType
{
    private const TYPE = 'app_ticket_id';

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
