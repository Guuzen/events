<?php

namespace App\Infrastructure\Http;

use App\Infrastructure\InlineNormalizer\InlineNormalizer;
use App\Infrastructure\MoneyNormalizer;
use App\Infrastructure\WithoutConstructorPropertyNormalizer;
use DateTimeInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use function Symfony\Component\DependencyInjection\Loader\Configurator\inline_service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    // SERIALIZER todo need inline services ?
    $services->set('app.infrastructure.http.serializer.normalizer.datetime', DateTimeNormalizer::class)
        ->args(['$defaultContext' => [DateTimeNormalizer::FORMAT_KEY => DateTimeInterface::ATOM]]);

    $services->set('app.infrastructure.http.serializer.normalizer.array', ArrayDenormalizer::class);

    $services->set('app.infrastructure.http.serializer.php_doc_extractor', PhpDocExtractor::class);

    $services->set('app.infrastructure.http.serializer.inline_normalizer', InlineNormalizer::class)
        ->args([service('annotations.reader')]);

    $services->set('app.infrastructure.http.serializer.property_normalizer', WithoutConstructorPropertyNormalizer::class)
        ->args(
            [
                service('serializer.mapping.class_metadata_factory'),
                null,
                service('app.infrastructure.http.serializer.php_doc_extractor'),
                service('serializer.mapping.class_discriminator_resolver')
            ]
        );

    $services->set('app.infrastructure.http.get_set_method_normalizer', GetSetMethodNormalizer::class);

    $services->set('app.infrastructure.http.serializer', Serializer::class)
        ->args(
            [
                '$normalizers' => [
                    service('app.infrastructure.http.serializer.normalizer.datetime'),
                    service('app.infrastructure.http.serializer.normalizer.array'),
                    inline_service(MoneyNormalizer::class),
                    service('app.infrastructure.http.serializer.inline_normalizer'),
                    service('serializer.normalizer.json_serializable'),
                    service('app.infrastructure.http.get_set_method_normalizer'),
                    service('app.infrastructure.http.serializer.property_normalizer'),
                ],
                '$encoders'    => [service('serializer.encoder.json')],
            ]
        );
};