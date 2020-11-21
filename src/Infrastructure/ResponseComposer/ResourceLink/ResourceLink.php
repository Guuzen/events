<?php

declare(strict_types=1);

namespace App\Infrastructure\ResponseComposer\ResourceLink;

use App\Infrastructure\ResponseComposer\ResponseBuilder\ResponseBuilder;
use App\Infrastructure\ResponseComposer\ResponseBuilder\SingleBuilder;

interface ResourceLink
{
    /**
     * @param SingleBuilder[] $builders
     *
     * @return ResponseBuilder[]
     */
    public function group(array $builders): array;

    public function defaultEmptyValue(): ResponseBuilder;
}
