<?php

namespace App\Infrastructure\Notifier\Email;

use Swift_Message;

interface Factory
{
    public function create(array $event): Swift_Message;
}
