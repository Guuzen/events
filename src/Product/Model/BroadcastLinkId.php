<?php


namespace App\Product\Model;

use Ramsey\Uuid\Uuid;

final class BroadcastLinkId
{
    private $id;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function equals(BroadcastLinkId $broadcastLinkId): bool
    {
        return $this->id->equals($broadcastLinkId->id);
    }
}
