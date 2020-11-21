<?php
declare(strict_types=1);

namespace App\Infrastructure\ResponseComposer\ResponseBuilder;

interface ResponseBuilder
{
    /**
     * @return object|object[]|null
     */
    public function build();
}
