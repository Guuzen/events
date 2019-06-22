<?php
declare(strict_types=1);

namespace App\Product\Model;

use Ramsey\Uuid\Uuid;

final class TicketId
{
    private $id;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function equals(TicketId $ticketId): bool
    {
        return $this->id->equals($ticketId->id);
    }

//    public static function cretae
}
