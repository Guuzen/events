<?php

namespace App\Integrations\Email\SendTicket;

use App\Integrations\Email\SendTicket\TicketDelivery\TicketDelivery;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(TicketDelivery::class)
        ->args(
            [
                '$mailer'   => service('app.infrastructure.email.mailer'),
                '$from'     => '%app.no_reply_email%',
                '$composer' => service('app.ticket_composer'),
            ]
        );
};
