<?php

namespace App\Event\Action;

// TODO validation
use App\Infrastructure\Http\AppRequest;

final class CreateEvent implements AppRequest
{
    /**
     * @readonly
     */
    public $name;

    /**
     * @readonly
     */
    public $domain;

    public function __construct(string $name, string $domain)
    {
        $this->name   = $name;
        $this->domain = $domain;
    }
}
