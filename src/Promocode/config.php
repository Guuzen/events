<?php

namespace App\Promocode;

use App\Infrastructure\MoneyNormalizer;
use App\Infrastructure\Persistence\DatabaseSerializer\DatabaseSerializer;
use App\Infrastructure\ResComposer\PromiseCollection;
use App\Infrastructure\ResComposer\ResourceComposer;
use App\Infrastructure\ResComposer\ResourceDenormalizer;
use App\Infrastructure\ResComposer\SkipInnerResourcesDenormalizer;
use App\Promocode\Frontend\OrderHasOnePromocode;
use App\Promocode\Frontend\PromocodeLoader;
use App\Promocode\Model\Promocodes;
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

    $services->set(Promocodes::class);

    $services->set('app.frontend.response_composer.promise_collection', PromiseCollection::class);

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

    $orderHasOnePromocode = inline(OrderHasOnePromocode::class)->args(
        [
            inline(PromocodeLoader::class)->args(
                [
                    ref(\Doctrine\DBAL\Connection::class),
                    ref(DatabaseSerializer::class),
                ]
            )
        ]
    );
    $services->set(ResourceComposer::class)->args(
        [
            inline(ResourceDenormalizer::class)->args(
                [
                    ref('app.frontend.response_composer.promise_collection'),
                    $denormalizer,
                ]
            ),
            ref('app.frontend.response_composer.promise_collection'),
        ]
    )->call('addResolver', [$orderHasOnePromocode]);
};