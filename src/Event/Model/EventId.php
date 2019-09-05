<?php

namespace App\Event\Model;

use App\Infrastructure\Uuid;

/**
 * @template-extends Uuid<EventId>
 */
final class EventId extends Uuid
{
}
