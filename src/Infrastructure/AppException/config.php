<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Infrastructure\AppException\AppExceptionListener;

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