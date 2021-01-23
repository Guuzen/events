<?php

namespace App\Infrastructure\Persistence\DatabaseSerializer;

use App\Infrastructure\MoneyNormalizer;
use App\Infrastructure\ResComposer\PromiseCollection;
use App\Infrastructure\ResComposer\ResourceComposer;
use App\Infrastructure\ResComposer\ResourceDenormalizer;
use App\Infrastructure\ResComposer\SkipInnerResourcesDenormalizer;
use DateTimeInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
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

    $denormalizer = inline(Serializer::class)->args(
        [
            '$normalizers' => [
                $dateTimeNormalizer,
                inline(SkipInnerResourcesDenormalizer::class),
                inline(ArrayDenormalizer::class),
                inline(MoneyNormalizer::class),
                inline(ObjectNormalizer::class)->args(
                    [
                        ref('serializer.mapping.class_metadata_factory'),
                        null,
                        ref('property_accessor'),
                        inline(PhpDocExtractor::class),
                        ref('serializer.mapping.class_discriminator_resolver')
                    ]
                )
            ]
        ]
    );
    $services->set(PromiseCollection::class);
    $services->set(ResourceComposer::class)->args(
        [
            inline(ResourceDenormalizer::class)->args(
                [
                    ref(PromiseCollection::class),
                    $denormalizer,
                ]
            ),
            ref(PromiseCollection::class),
        ]
    );
};
