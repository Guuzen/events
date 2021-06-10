<?php

namespace App\Integrations\Email;

use Guuzen\ResourceComposer\ResourceComposer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(OnTicketOrderPaymentConfirmed::class);

    $services->set(TicketResource::class);

    $services->set(UserResource::class);

    $services->set('app.ticket_composer', ResourceComposer::class)
        ->call('registerMainResource', [service(TicketResource::class)])
        ->call('registerRelatedResource', [service(UserResource::class)]);
};
