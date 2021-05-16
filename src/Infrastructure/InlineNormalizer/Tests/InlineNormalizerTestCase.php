<?php

declare(strict_types=1);

namespace App\Infrastructure\InlineNormalizer\Tests;

use App\Infrastructure\InlineNormalizer\InlineNormalizer;
use App\Infrastructure\Persistence\PersistencePropertyNormalizer;
use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class InlineNormalizerTestCase extends TestCase
{
    /**
     * @var InlineNormalizer
     */
    protected $normalizer;

    protected function setUp(): void
    {
        $reader               = new AnnotationReader();
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader($reader));
        $phpDocExtractor      = new PhpDocExtractor();
        $this->normalizer     = new InlineNormalizer($reader, $classMetadataFactory);
        $propertyNormalizer   = new PersistencePropertyNormalizer($classMetadataFactory, null, $phpDocExtractor);
        $serializer           = new Serializer(
            [
                new ArrayDenormalizer(),
                $this->normalizer,
                $propertyNormalizer,
            ]
        );

        $propertyNormalizer->setSerializer($serializer);
        $this->normalizer->setDenormalizer($propertyNormalizer);
        $this->normalizer->setNormalizer($serializer);
    }

    abstract public function testNormalize(): void;

    abstract public function testDenormalize(): void;
}
