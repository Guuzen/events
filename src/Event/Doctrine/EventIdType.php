<?php

namespace App\Event\Doctrine;

use App\Event\Model\EventId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class EventIdType extends StringType
{
    private const TYPE = 'app_event_id';

    public function convertToPHPValue($value, AbstractPlatform $platform): EventId
    {
        // TODO create via reflection ?
        return EventId::fromString($value);
    }

    public function getName(): string
    {
        return self::TYPE;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
