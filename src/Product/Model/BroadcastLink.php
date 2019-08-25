<?php

namespace App\Product\Model;

use App\Event\Model\EventId;

final class BroadcastLink
{
    private $id;

    private $eventId;

    private $link;

    private $reserved;

    public function __construct(BroadcastLinkId $id, EventId $eventId, string $link, bool $reserved = false)
    {
        $this->id       = $id;
        $this->eventId  = $eventId;
        $this->link     = $link;
        $this->reserved = $reserved;
    }

    public function reserve(): void
    {
        $this->reserved = true;
    }

    public function cancelReserve(): void
    {
        $this->reserved = false;
    }
}
