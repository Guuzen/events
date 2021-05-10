<?php

namespace App\Infrastructure\Email;

use GuzzleHttp\Client;
use Swift_Mailer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\inline_service;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set('app.infrastructure.email.mailer', Swift_Mailer::class)
        ->args(
            [
                '$transport' => inline_service(HttpEmailTransport::class)
                    ->args(
                        [
                            '$client' => inline_service(Client::class)
                                ->args(
                                    [
                                        '$config' => [
                                            'base_uri' => '%env(TEST_MAILER_HOST)%',
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