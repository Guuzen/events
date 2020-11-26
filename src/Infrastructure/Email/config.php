<?php

namespace App\Infrastructure\Email;

use GuzzleHttp\Client;
use Swift_Mailer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\inline;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set('app.infrastructure.email.mailer', Swift_Mailer::class)
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
        );
};