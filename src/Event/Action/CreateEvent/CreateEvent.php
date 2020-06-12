<?php

declare(strict_types=1);

namespace App\Event\Action\CreateEvent;

/**
 * @psalm-immutable
 */
final class CreateEvent
{
    public $name;

    public $domain;

    public function __construct(string $name, string $domain)
    {
        $this->name   = $name;
        $this->domain = $domain;
    }
}
