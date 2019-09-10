<?php

namespace App\Queries\Tariff\FindTariffById;

use App\Infrastructure\Http\AppRequest;

/**
 * @psalm-immutable
 */
final class FindTariffById implements AppRequest
{
    public $tariffId;

    public function __construct(string $tariffId)
    {
        $this->tariffId = $tariffId;
    }
}
