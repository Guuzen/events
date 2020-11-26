<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\User\Model\Users;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(Users::class);
};