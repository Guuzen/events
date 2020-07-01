<?php

declare(strict_types=1);

namespace App\Tariff\Query\GetTariffList;

use App\Infrastructure\Http\RequestResolver\AppRequest;

/**
 * @psalm-immutable
 */
final class GetTariffListRequest implements AppRequest
{
    public $eventId;

    public function __construct(string $eventId)
    {
        $this->eventId = $eventId;
    }
}
