<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Infrastructure\InlineNormalizer\InlineNormalizer;
use App\Infrastructure\WithoutConstructorPropertyNormalizer;
use DateTimeInterface;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

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
        ->args([ref('annotations.reader')]);

    $services->set('app.infrastructure.http.serializer.property_normalizer', WithoutConstructorPropertyNormalizer::class)
        ->args(
            [
                ref('serializer.mapping.class_metadata_factory'),
                null,
                ref('app.infrastructure.http.serializer.php_doc_extractor'),
                ref('serializer.mapping.class_discriminator_resolver')
            ]
        );

    $services->set('app.infrastructure.http.get_set_method_normalizer', GetSetMethodNormalizer::class);

    $services->set('app.infrastructure.http.serializer', Serializer::class)
        ->args(
            [
                '$normalizers' => [
                    ref('app.infrastructure.http.serializer.normalizer.datetime'),
                    ref('app.infrastructure.http.serializer.normalizer.array'),
                    ref('app.infrastructure.http.serializer.inline_normalizer'),
                    ref('serializer.normalizer.json_serializable'),
                    ref('app.infrastructure.http.get_set_method_normalizer'),
                    ref('app.infrastructure.http.serializer.property_normalizer'),
                ],
                '$encoders'    => [ref('serializer.encoder.json')],
            ]
        );
};