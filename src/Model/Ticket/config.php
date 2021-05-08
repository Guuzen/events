<?php

namespace App\Model\Ticket;

use App\Model\Ticket\OnOrderEvent\OnTicketOrderPaymentConfirmed;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(Tickets::class);

    $services->set(OnTicketOrderPaymentConfirmed::class);
};