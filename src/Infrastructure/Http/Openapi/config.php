<?php

namespace App\Infrastructure\Http\Openapi;

use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\inline_service;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(OpenapiSchema::class)
        ->args(
            [
                '$pathToOasSpec' => '%kernel.project_dir%/src/Infrastructure/Http/Openapi/schema/stoplight.yaml',
                '$cache'         => inline_service(PhpFilesAdapter::class)
                    ->args(['', 0, '%kernel.cache_dir%/openapi'])
            ]
        );

    $services->set(OpenapiValidator::class);
};