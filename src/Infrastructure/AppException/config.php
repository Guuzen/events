<?php

namespace App\Infrastructure\AppException;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(AppExceptionListener::class)->tag(
        'kernel.event_listener', [
            'event'    => 'kernel.exception',
            'priority' => -100
        ]
    );
};