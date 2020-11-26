<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Infrastructure\Email\HttpEmailTransport;
use App\Product\Action\SendTicket\FindTicketEmail\FindTicketEmail;
use App\Product\Action\SendTicket\TicketSending\TicketSending;
use GuzzleHttp\Client;
use Swift_Mailer;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(FindTicketEmail::class);

    // TODO email transport should be in Infrastructure ?
    $services->set(TicketSending::class)
        ->args(
            [
                '$mailer' => inline(Swift_Mailer::class)
                    ->args(
                        [
                            '$transport' => inline(HttpEmailTransport::class)
                                ->args(
                                    [
                                        '$client' => inline(Client::class)
                                            ->args(
                                                [
                                                    '$config' => [
                                                        'base_uri' => 'http://localhost:8001',
                                                    ]
                                                ]
                                            )
                                        ,
                                    ]
                                )
                            ,
                        ]
                    )
                ,
                '$from'   => '%app.no_reply_email%',
            ]
        );
};