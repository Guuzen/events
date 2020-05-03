<?php

namespace App\Event\Model;

use App\Infrastructure\Uuid;

/**
 * @template-extends Uuid<EventId>
 * @psalm-immutable
 */
final class EventId extends Uuid
{
}
