<?php

namespace App\Infrastructure\Persistence\DatabaseSerializer;

use App\Infrastructure\InlineNormalizer\InlineNormalizer;
use App\Infrastructure\MoneyNormalizer;
use App\Infrastructure\WithoutConstructorPropertyNormalizer;
use DateTimeInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;
use function Symfony\Component\DependencyInjection\Loader\Configurator\inline;
use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $dateTimeNormalizer = inline(DateTimeNormalizer::class)
        ->args(
            [
                '$defaultContext' => [
                    DateTimeNormalizer::FORMAT_KEY   => DateTimeInterface::ATOM,
                    DateTimeNormalizer::TIMEZONE_KEY => 'UTC',
                ]
            ]
        );
    $inlineNormalizer   = inline(InlineNormalizer::class)
        ->args(
            [
                ref('annotations.reader'),
                ref('serializer.mapping.class_metadata_factory'),
                ref('serializer.name_converter.camel_case_to_snake_case'),
                ref('property_info.php_doc_extractor'),
                ref('serializer.mapping.class_discriminator_resolver'),
            ]
        );
    $propertyNormalizer = inline(WithoutConstructorPropertyNormalizer::class)
        ->args(
            [
                ref('serializer.mapping.class_metadata_factory'),
                ref('serializer.name_converter.camel_case_to_snake_case'),
                ref('property_info.php_doc_extractor'),
                ref('serializer.mapping.class_discriminator_resolver'),
            ]
        );

    $services->set(DatabaseSerializer::class)
        ->args(
            [
                inline(Serializer::class)
                    ->args(
                        [
                            '$normalizers' => [
                                $dateTimeNormalizer,
                                ref('serializer.denormalizer.array'),
                                inline(MoneyNormalizer::class),
                                $inlineNormalizer,
                                $propertyNormalizer,
                            ],
                            '$encoders'    => [ref('serializer.encoder.json')]
                        ]
                    )
                ,
            ]
        );
};
