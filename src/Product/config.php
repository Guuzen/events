<?php

namespace App\Product;

use App\Product\OnOrderEvent\SendTicket\FindTicketEmail\FindTicketEmail;
use App\Product\OnOrderEvent\SendTicket\TicketSending\TicketSending;
use App\Product\Model\Tickets;
use App\Product\OnOrderEvent\OnOrderMarkedPaid;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(Tickets::class);

    $services->set(FindTicketEmail::class);

    $services->set(TicketSending::class)
        ->args(
            [
                '$mailer' => ref('app.infrastructure.email.mailer'),
                '$from'   => '%app.no_reply_email%',
            ]
        );

    $services->set(OnOrderMarkedPaid::class);
};