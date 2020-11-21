<?php

declare(strict_types=1);

namespace App\Infrastructure\ResponseComposer\ResponseBuilder;

final class NullBuilder implements ResponseBuilder
{
    public function build()
    {
        return null;
    }
}
