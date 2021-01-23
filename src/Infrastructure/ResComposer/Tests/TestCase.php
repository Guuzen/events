<?php

declare(strict_types=1);

namespace App\Infrastructure\ResComposer\Tests;

use App\Infrastructure\ResComposer\PromiseCollection;
use App\Infrastructure\ResComposer\ResourceComposer;
use App\Infrastructure\ResComposer\ResourceDenormalizer;
use App\Infrastructure\ResComposer\SkipInnerResourcesDenormalizer;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorFromClassMetadata;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ResourceComposer
     */
    protected $composer;

    protected function setUp(): void
    {
        $promises             = new PromiseCollection();
        $annotationReader     = new AnnotationReader();
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader($annotationReader));
        $denormalizer         = new Serializer(
            [
                new ArrayDenormalizer(),
                new SkipInnerResourcesDenormalizer(),
                new ObjectNormalizer(
                    $classMetadataFactory,
                    null,
                    PropertyAccess::createPropertyAccessor(),
                    new PhpDocExtractor(),
                    new ClassDiscriminatorFromClassMetadata($classMetadataFactory),
                )
            ]
        );
        $resourceDenormalizer = new ResourceDenormalizer($promises, $denormalizer);
        $this->composer       = new ResourceComposer($resourceDenormalizer, $promises);
    }

    abstract public function test(): void;
}
