<?php

declare(strict_types=1);

namespace App\Adapters\AdminApi\Promocode\GetPromocodeList;

use App\Infrastructure\Http\RequestResolver\AppRequest;

/**
 * @psalm-immutable
 */
final class GetPromocodeListRequest implements AppRequest
{
    public $eventId;

    public function __construct(string $eventId)
    {
        $this->eventId = $eventId;
    }
}
