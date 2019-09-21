<?php

namespace App\Tests\Contract\AppResponse;

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

    public static function any(): self
    {
        return new self('Thanks for buy ticket', 'no-reply@event.com', 'john@email.com');
    }
}
