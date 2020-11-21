<?php

declare(strict_types=1);

namespace App\Infrastructure\ResponseComposer;

/** TODO remove this interface ? */
interface SchemaProvider
{
    public static function schema(): Schema;
}
