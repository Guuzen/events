<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\DoctrineTypesInitializer;

/**
 * @Annotation
 *
 * @Target({"CLASS"})
 */
final class InitializerAnnotation
{
    /**
     * @Required
     *
     * @var string
     */
    public $doctrineType;
}
