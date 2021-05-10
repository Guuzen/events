<?php

namespace App\Integrations\Email\SendTicket;

use App\Infrastructure\Persistence\ResourceComposer\PostgresLoader;
use App\Integrations\Email\SendTicket\TicketDelivery\TicketDelivery;
use Guuzen\ResourceComposer\Config\MainResource;
use Guuzen\ResourceComposer\Config\RelatedResource;
use Guuzen\ResourceComposer\Link\OneToOne;
use Guuzen\ResourceComposer\PromiseCollector\SimpleCollector;
use Guuzen\ResourceComposer\ResourceComposer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\inline;
use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set('app.integrations.email.send_ticket.user_loader', PostgresLoader::class)
        ->args(
            [
                '$table'       => '"user"',
                '$searchField' => 'id',
            ]
        );
    $services->set('app.ticket_composer', ResourceComposer::class)
        ->call(
            'registerRelation', [
                inline(MainResource::class)->args(
                    [
                        'ticket',
                        inline(SimpleCollector::class)->args(['user_id', 'user'])
                    ],
                ),
                inline(OneToOne::class),
                inline(RelatedResource::class)->args(
                    [
                        'user',
                        'id',
                        ref('app.integrations.email.send_ticket.user_loader')
                    ]
                )
            ]
        );

    $services->set(TicketDelivery::class)
        ->args(
            [
                '$mailer'   => ref('app.infrastructure.email.mailer'),
                '$from'     => '%app.no_reply_email%',
                '$composer' => ref('app.ticket_composer'),
            ]
        );
};
