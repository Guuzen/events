<?php

namespace App\EventFoo;

use Ramsey\Uuid\UuidInterface;

class Visitor
{
    private $id;

    private $lang;

    private $country;

    private $city;

    private $ip;

    public function __construct(UuidInterface $id, string $lang, string $country, string $city, string $ip)
    {
        $this->id      = $id;
        $this->lang    = $lang;
        $this->country = $country;
        $this->city    = $city;
        $this->ip      = $ip;
    }
}
