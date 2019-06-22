<?php
declare(strict_types=1);

namespace App\Tariff\Model;

interface Tariffs
{
    public function getById(TariffId $tariffId): Tariff;
}
