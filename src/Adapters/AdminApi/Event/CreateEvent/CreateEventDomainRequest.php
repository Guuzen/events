<?php

declare(strict_types=1);

namespace App\Adapters\AdminApi\Event\CreateEvent;

use App\Infrastructure\Http\RequestResolver\AppRequest;

/**
 * @psalm-immutable
 */
final class CreateEventDomainRequest implements AppRequest
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $domain;
}
