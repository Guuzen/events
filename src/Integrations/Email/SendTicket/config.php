<?php

namespace App\Integrations\Email\SendTicket;

use App\Integrations\Email\SendTicket\FindTicketEmail\FindTicketEmail;
use App\Integrations\Email\SendTicket\TicketDelivery\TicketDelivery;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(FindTicketEmail::class);

    $services->set(TicketDelivery::class)
        ->args(
            [
                '$mailer' => ref('app.infrastructure.email.mailer'),
                '$from'   => '%app.no_reply_email%',
            ]
        );
};