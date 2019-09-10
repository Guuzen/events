<?php

namespace App\Tariff\Action;

use App\Infrastructure\Http\AppRequest;

/**
 * @psalm-immutable
 */
final class CreateTariff implements AppRequest
{
    public $eventId;

    public $tariffType;

    /**
     * @var CreateTariffSegment[]
     */
    public $segments;

    /**
     * @param CreateTariffSegment[] $segments
     */
    public function __construct(string $eventId, string $tariffType, array $segments)
    {
        $this->eventId    = $eventId;
        $this->tariffType = $tariffType;
        $this->segments   = $segments;
    }
}
