<?php

namespace App\Queries\Tariff;

use App\Common\AppRequest;

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
