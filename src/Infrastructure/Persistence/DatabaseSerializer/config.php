<?php

namespace App\Infrastructure\Persistence\DatabaseSerializer;

use App\Infrastructure\InlineNormalizer\InlineNormalizer;
use App\Infrastructure\MoneyNormalizer;
use App\Infrastructure\WithoutConstructorPropertyNormalizer;
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

    $dateTimeNormalizer = inline_service(DateTimeNormalizer::class)
        ->args(
            [
                '$defaultContext' => [
                    DateTimeNormalizer::FORMAT_KEY   => DateTimeInterface::ATOM,
                    DateTimeNormalizer::TIMEZONE_KEY => 'UTC',
                ]
            ]
        );

    $services->set('app.db.property_normalizer', WithoutConstructorPropertyNormalizer::class)
        ->args(
            [
                service('serializer.mapping.class_metadata_factory'),
                service('serializer.name_converter.camel_case_to_snake_case'),
                service('property_info.php_doc_extractor'),
                service('serializer.mapping.class_discriminator_resolver'),
            ]
        );

    $inlineNormalizer = inline_service(InlineNormalizer::class)
        ->args(
            [
                service('annotations.reader'),
                service('serializer.mapping.class_metadata_factory'),
            ]
        )
        ->call('setDenormalizer', [service('app.db.property_normalizer')])
        ->call('setNormalizer', [service('app.db.serializer')]);

    $services->set('app.db.serializer', Serializer::class)
        ->args(
            [
                '$normalizers' => [
                    $dateTimeNormalizer,
                    service('serializer.denormalizer.array'),
                    inline_service(MoneyNormalizer::class),
                    $inlineNormalizer,
                    service('app.db.property_normalizer'),
                ],
                '$encoders'    => [service('serializer.encoder.json')]
            ]
        );

    $services->set(DatabaseSerializer::class)
        ->args([service('app.db.serializer')]);
};
