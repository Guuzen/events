<?php

declare(strict_types=1);

namespace App\Queries\Event\CreateEvent;

use App\Infrastructure\Http\RequestResolver\AppRequest;

/**
 * @psalm-immutable
 */
final class CreateEventRequest implements AppRequest
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
