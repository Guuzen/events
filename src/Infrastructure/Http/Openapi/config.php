<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Infrastructure\Http\Openapi\OpenapiSchema;
use App\Infrastructure\Http\Openapi\OpenapiValidator;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set(OpenapiSchema::class)
        ->args(
            [
                '$pathToOasSpec' => '%kernel.root_dir%/Infrastructure/Http/Openapi/schema/stoplight.yaml',
                '$cache'         => inline(PhpFilesAdapter::class)
                    ->args(['', 0, '%kernel.cache_dir%/openapi'])
            ]
        );

    $services->set(OpenapiValidator::class);
};