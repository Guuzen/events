<?php
declare(strict_types=1);

namespace App\Event\Model;

use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Promocode\Model\Exception\CantCalculateSum;
use App\Promocode\Model\Promocode;
use App\Tariff\Model\Tariff;
use App\Tariff\Model\TariffId;
use App\Tariff\Model\Tariffs;
use App\User\Model\User;
use DateTimeImmutable;

final class ActiveTariff
{
    private $tariffId;

    private $activeTariffType;

    public function __construct(TariffId $tariffId, ActiveTariffType $activeTariffType)
    {
        $this->tariffId         = $tariffId;
        $this->activeTariffType = $activeTariffType;
    }

    public function getTariff(Tariffs $tariffs): Tariff
    {
        return $tariffs->getById($this->tariffId);
    }

    public function sameTypeAs(ActiveTariffType $activeTariffType): bool
    {
        return $this->activeTariffType->equals($activeTariffType);
    }
}
