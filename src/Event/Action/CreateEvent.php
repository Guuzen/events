<?php

namespace App\Event\Action;

// TODO validation
use App\Common\AppRequest;

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
