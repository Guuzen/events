<?php

namespace App\Tests\AppResponse\EmailWithTicket;

final class EmailWithTicketBuilder
{
    private $subject;

    private $from;

    private $to;

    private function __construct(string $subject, string $from, string $to)
    {
        $this->subject = $subject;
        $this->from    = $from;
        $this->to      = $to;
    }

    public static function any(): self
    {
        return new self('Thanks for buy ticket', 'no-reply@event.com', 'john@email.com');
    }

    public function build(): EmailWithTicket
    {
        return new EmailWithTicket($this->subject, $this->from, $this->to);
    }

    public function withSubject(string $subject): self
    {
        return new self($subject, $this->from, $this->to);
    }

    public function withFrom(string $from): self
    {
        return new self($this->subject, $from, $this->to);
    }

    public function withTo(string $to): self
    {
        return new self($this->subject, $this->from, $to);
    }
}
