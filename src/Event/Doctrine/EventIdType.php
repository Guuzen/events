<?php

namespace App\Event\Doctrine;

use App\Event\Model\EventId;
use App\Infrastructure\Persistence\UuidType;

/**
 * @template-extends UuidType<EventId>
 */
final class EventIdType extends UuidType
{
    protected function className(): string
    {
        return EventId::class;
    }

    public function getName(): string
    {
        return 'app_event_id';
    }
}
