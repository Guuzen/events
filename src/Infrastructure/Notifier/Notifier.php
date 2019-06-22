<?php
declare(strict_types=1);

namespace App\Infrastructure\Notifier;

interface Notifier
{
    public function notify(array $event);
}
