<?php

namespace App\Tariff\Query\FindTariffById;

use App\Infrastructure\Http\RequestResolver\AppRequest;

/**
 * @psalm-immutable
 */
final class FindTariffByIdRequest implements AppRequest
{
    public $tariffId;

    public function __construct(string $tariffId)
    {
        $this->tariffId = $tariffId;
    }
}
