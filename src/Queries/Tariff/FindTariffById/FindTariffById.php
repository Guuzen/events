<?php

namespace App\Queries\Tariff\FindTariffById;

use App\Infrastructure\Http\AppRequest;

final class FindTariffById implements AppRequest
{
    /**
     * @readonly
     */
    public $tariffId;

    public function __construct(string $tariffId)
    {
        $this->tariffId = $tariffId;
    }
}
