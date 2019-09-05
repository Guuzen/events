<?php

namespace App\Event\Doctrine;

use App\Event\Model\EventId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class EventIdType extends StringType
{
    public function convertToPHPValue($value, AbstractPlatform $platform): EventId
    {
        // TODO create via reflection ?
        return new EventId((string) $value);
    }

    public function getName(): string
    {
        return 'app_event_id';
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
