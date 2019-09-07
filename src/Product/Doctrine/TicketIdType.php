<?php

namespace App\Product\Doctrine;

use App\Infrastructure\Persistence\UuidType;
use App\Product\Model\TicketId;

/**
 * @template-extends UuidType<TicketId>
 */
final class TicketIdType extends UuidType
{
    public function getName(): string
    {
        return 'app_ticket_id';
    }

    protected function className(): string
    {
        return TicketId::class;
    }
}
