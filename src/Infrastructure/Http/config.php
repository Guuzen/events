<?php

namespace App\Infrastructure\Http;

use DateTimeInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;
use function Symfony\Component\DependencyInjection\Loader\Configurator\inline_service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->set('app.infrastructure.http.serializer', Serializer::class)
        ->args(
            [
                '$normalizers' => [
                    inline_service(DateTimeNormalizer::class)
                        ->args(['$defaultContext' => [DateTimeNormalizer::FORMAT_KEY => DateTimeInterface::ATOM]])
                    ,
                    service('serializer.denormalizer.array'),
                    service('serializer.normalizer.property')
                ],
                '$encoders'    => [service('serializer.encoder.json')],
            ]
        );
};