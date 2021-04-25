<?php

namespace App\Adapters\AdminApi\Product\FindTicketById;

use App\Infrastructure\Http\RequestResolver\AppRequest;

/**
 * @psalm-immutable
 */
final class FindTicketByIdRequest implements AppRequest
{
    public $ticketId;

    public function __construct(string $ticketId)
    {
        $this->ticketId = $ticketId;
    }
}
