<?php
declare(strict_types=1);

namespace App\Infrastructure\Notifier\Email;

use Swift_Message;

interface Factory
{
    public function create(array $event): Swift_Message;
}
