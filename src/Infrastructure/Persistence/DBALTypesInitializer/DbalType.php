<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\DBALTypesInitializer;

/**
 * @Annotation
 *
 * @Target({"CLASS"})
 */
final class DbalType
{
    /**
     * @Required
     *
     * @var string
     */
    public $typeClass;
}
