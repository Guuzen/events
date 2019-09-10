<?php

namespace App\Tariff\Action;

use App\Infrastructure\Http\AppRequest;

// TODO replace readonly with immutable
final class CreateTariff implements AppRequest
{
    /**
     * @readonly
     */
    public $eventId;

    /**
     * @readonly
     */
    public $tariffType;

    /**
     * @readonly
     *
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
