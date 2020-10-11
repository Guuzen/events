<?php

declare(strict_types=1);

namespace App\Order\Query\GetOrderList;

use App\Event\Model\EventId;

/**
 * @psalm-immutable
 */
final class GetOrderList
{
    public $eventId;

    public function __construct(EventId $eventId)
    {
        $this->eventId = $eventId;
    }
}
