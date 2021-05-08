<?php

namespace App\Model\Promocode;

use App\Adapters\AdminApi\Promocode\PromocodeLoader;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(Promocodes::class);

    $services->set(PromocodeLoader::class);

    $services->set(OnTicketOrderPaymentConfirmed::class);
};
