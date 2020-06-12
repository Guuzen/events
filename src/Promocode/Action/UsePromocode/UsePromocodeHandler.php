<?php

declare(strict_types=1);

namespace App\Promocode\Action\UsePromocode;

use App\Common\Error;
use App\Order\Model\Orders;
use App\Promocode\Model\FixedPromocodes;
use App\Tariff\Model\Tariffs;
use DateTimeImmutable;

// TODO rename use fixed promocode ?
final class UsePromocodeHandler
{
    private $fixedPromocodes;

    private $tariffs;

    private $orders;

    public function __construct(FixedPromocodes $fixedPromocodes, Tariffs $tariffs, Orders $orders)
    {
        $this->fixedPromocodes = $fixedPromocodes;
        $this->tariffs         = $tariffs;
        $this->orders          = $orders;
    }

    public function handle(UsePromocode $command): ?Error
    {
        $promocode = $this->fixedPromocodes->findByCode($command->code);

        if ($promocode instanceof Error) {
            return $promocode;
        }

        $tariff = $this->tariffs->findById($command->tariffId);
        if ($tariff instanceof Error) {
            return $tariff;
        }

        $order = $this->orders->findById($command->orderId);
        if ($order instanceof Error) {
            return $order;
        }

        $promocode->use($command->orderId, $command->tariffId, $tariff, new DateTimeImmutable());
        $order->applyPromocode($promocode);

        return null;
    }
}
