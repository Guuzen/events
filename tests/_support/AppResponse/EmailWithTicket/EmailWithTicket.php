<?php

namespace App\Tests\AppResponse\EmailWithTicket;

final class EmailWithTicket
{
    private $subject;

    private $from;

    private $to;

    public function __construct(string $subject, string $from, string $to)
    {
        $this->subject = $subject;
        $this->from    = $from;
        $this->to      = $to;
    }
}
